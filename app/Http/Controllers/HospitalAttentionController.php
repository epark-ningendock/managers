<?php

namespace App\Http\Controllers;

use App\Hospital;
use App\HospitalDetail;
use App\HospitalMiddleClassification;
use App\HospitalMinorClassification;
use App\FeeRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class HospitalAttentionController extends Controller
{
    /**
     * 医療機関こだわり情報画面に遷移する
     *
     * @param Int 医療機関ID
     * @return 医療機関こだわり情報画面
     */
    public function create($hospital_id)
    {
        $middles = HospitalMiddleClassification::all();

        $hospital = Hospital::findOrFail($hospital_id);

        // 通常手数料
        $feeRates = FeeRate::where('hospital_id', $hospital_id)->where('type', FeeRate::FEE_RATE)->orderBy('from_date', 'asc')->get();

        // 事前決済手数料
        $prePaymentFeeRates = FeeRate::where('hospital_id', $hospital_id)->where('type', FeeRate::PRE_PAYMENT_FEE_RATE)->orderBy('from_date', 'asc')->get();

        return view('hospital.create-attention')
            ->with('hospital', $hospital)
            ->with('middles', $middles)
            ->with('feeRates', $feeRates)
            ->with('prePaymentFeeRates', $prePaymentFeeRates);
    }

    /**
     * 医療機関こだわり情報を保存する
     *
     * @param Request 医療機関こだわり情報, 手数料
     * @param Int 医療機関ID
     * @return 医療機関一覧画面
     */
    public function store(Request $request, int $hospital_id)
    {

        try {
            $this->validate($request, [
                'pvad' => 'numeric|digits_between:1,10'
            ]);
    
            try {
                DB::beginTransaction();
    
                // 通常手数料
                if ($request->input('fee_rate_ids')) {
                    foreach($request->input('fee_rate_ids') as $index => $fee_rate_id) {
                        $fee_rates_array[$index] = [
                            'id' => $fee_rate_id,
                            'rate' => $request->input('rates')[$index],
                            'from_date' => new Carbon($request->input('from_dates')[$index])
                        ];
                    }
                    
                    // 期間(開始)を照準でソートする
                    $fee_rates = collect($fee_rates_array);
                    $sorted_fee_rates = $fee_rates->sortBy('from_date')->values();
    
                    if($sorted_fee_rates->isNotEmpty()) {
                        foreach ($sorted_fee_rates as $key => $value) {
                            
                            $validator = Validator::make(["rate" => $value['rate'], "from_date" => $value['from_date']], [
                                'rate' => 'required|numeric|digits_between:1,10',
                                'from_date' => 'required|date'
                            ]);
                    
                            if ($validator->fails()) {
                                DB::rollback();
                                throw new ValidationException($validator);
                                return redirect()->back();
                            }

                            if (count($sorted_fee_rates) - 1 <= $key) {
                                $to_date = null;
                                $this->saveFeeRate($value, $hospital_id, FeeRate::FEE_RATE, $to_date);
                            } else {
                                $next_from_date = new Carbon($sorted_fee_rates[$key + 1]['from_date']);
                                if ($value['from_date'] == $next_from_date) {
                                    DB::rollback();
                                    $request->session()->flash('error', '適用期間が重複しています。');
                                    return redirect()->back();
                                }
                                
                                $date = new Carbon($sorted_fee_rates[$key + 1]['from_date']);
                                $to_date = $date->subDay();
                                $this->saveFeeRate($value, $hospital_id, FeeRate::FEE_RATE, $to_date);
                            }
                        }
                    }
                }
    
                // 事前決済手数料
                if ($request->input('pre_payment_fee_rate_ids')) {
                    foreach($request->input('pre_payment_fee_rate_ids') as $index => $pre_payment_fee_rate_id) {
                        $pre_payment_fee_rates_array[$index] = [
                            'id' => $pre_payment_fee_rate_id,
                            'rate' => $request->input('pre_payment_rates')[$index],
                            'from_date' => new Carbon($request->input('pre_payment_from_dates')[$index])
                        ];
                    }
        
                    // 期間(開始)を照準でソートする
                    $pre_payment_fee_rates = collect($pre_payment_fee_rates_array);
                    $sorted_pre_payment_fee_rates = $pre_payment_fee_rates->sortBy('from_date')->values();
    
                    if($sorted_pre_payment_fee_rates->isNotEmpty()) {
                        foreach ($sorted_pre_payment_fee_rates as $key => $value) {
                            
                            $validator = Validator::make(["pre_payment_rate" => $value['rate'], "pre_payment_from_date" => $value['from_date']], [
                                'pre_payment_rate' => 'required|numeric|between:0,99',
                                'pre_payment_from_date' => 'required|date'
                            ]);
                    
                            if ($validator->fails()) {
                                DB::rollback();
                                throw new ValidationException($validator);
                                return redirect()->back();
                            }

                            if (count($sorted_pre_payment_fee_rates) - 1 <= $key) {
                                $to_date = null;
                                $this->saveFeeRate($value, $hospital_id, FeeRate::PRE_PAYMENT_FEE_RATE, $to_date);
                            } else {
                                $next_from_date = new Carbon($sorted_pre_payment_fee_rates[$key + 1]['from_date']);
                                if ($value['from_date'] == $next_from_date) {
                                    DB::rollback();
                                    $request->session()->flash('error', '適用期間が重複しています。');
                                    return redirect()->back();
                                }
    
                                $to_date = new Carbon($sorted_pre_payment_fee_rates[$key + 1]['from_date']->subDay());
                                $this->saveFeeRate($value, $hospital_id, FeeRate::PRE_PAYMENT_FEE_RATE, $to_date);                       
                            }
                        }
                    }
                }
    
                $hospital = Hospital::findOrFail($hospital_id);
                $hospital->pvad = $request->get('pvad');
                if ($request->get('is_pickup')) {
                    $hospital->is_pickup = 1;
                } else {
                    $hospital->is_pickup = 0;
                }
                $hospital->save();
    
                $minor_ids = collect($request->input('minor_ids'), []);
                $minor_values = collect($request->input('minor_values'), []);
    
                if ($minor_ids->isNotEmpty()) {
                    $minors = HospitalMinorClassification::whereIn('id', $minor_ids)->orderBy('order')->get();
    
                    if ($minors->count() != count($minor_ids)) {
                        $request->session()->flash('error', trans('messages.invalid_minor_id'));
                        return redirect()->back();
                    }
    
                    $hospital->hospital_details()->forceDelete();
    
                    foreach ($minors as $index => $minor) {
                        $input_index = $minor_ids->search(function ($id) use ($minor) {
                            return $minor->id == $id;
                        });
                        
                        if ($input_index == -1 || ($minor->is_fregist == '1' && $minor_values[$input_index] == 0)
                            || ($minor->is_fregist == '0' && $minor_values[$input_index] == '')) {
                            continue;
                        }
    
    
                        $hospital_details = new HospitalDetail();
                        $hospital_details->hospital_id = $hospital->id;
                        $hospital_details->minor_classification_id = $minor->id;
                        if ($minor->is_fregist == '1') {
                            $hospital_details->select_status = 1;
                        } else if ($minor->is_fregist == '2') {
                            $hospital_details->select_status = 1;
                            $hospital_details->inputstring = $minor_values[$input_index];
                        }
                         else {
                            $hospital_details->inputstring = $minor_values[$input_index];
                        }
                        $hospital_details->save();
                    }
                }
    
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

            $middles = HospitalMiddleClassification::all();
            $hospital = Hospital::findOrFail($hospital_id);
            $feeRates = FeeRate::where('hospital_id', $hospital_id)->where('type', FeeRate::FEE_RATE)->orderBy('from_date', 'asc')->get();
            $prePaymentFeeRates = FeeRate::where('hospital_id', $hospital_id)->where('type', FeeRate::PRE_PAYMENT_FEE_RATE)->orderBy('from_date', 'asc')->get();
            return redirect()->route('hospital.attention.create', ['hospital_id' => $hospital_id])->with('success', trans('messages.updated', ['name' => trans('messages.names.attetion_information')]));

        } catch (Exception $e) {
            return redirect()->back()->withErrors(trans('messages.create_error'))->withInput();
        }
    }

    /**
     * 手数料率テーブルに保存する
     *
     * @param Array 手数料率
     * @param Int 医療機関ID
     * @param Int 手数料区分
     * @param 期間(終了)
     */
    protected function saveFeeRate(Array $value, int $hospital_id, int $type, $to_date) {
        if ($value['id']) {
            $fee_rate = FeeRate::findOrFail($value['id']);
            $fee_rate->rate = $value['rate'];
            $fee_rate->from_date = $value['from_date'];
            $fee_rate->to_date = $to_date;
        } else {
            $fee_rate = new FeeRate([
                'hospital_id' => $hospital_id,
                'type' => $type,
                'rate' => $value['rate'],
                'from_date' => $value['from_date'],
                'to_date' => $to_date
            ]);
        }
        $fee_rate->save();
    }
}
