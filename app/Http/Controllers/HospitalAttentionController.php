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

class HospitalAttentionController extends Controller
{
    /**
     * 医療機関こだわり情報画面に遷移する
     *
     * @param 医療機関ID
     * @return 医療機関こだわり情報画面
     */
    public function create()
    {
        $middles = HospitalMiddleClassification::all();

        // @todo 医療機関のIDを入れる
        $hospital = Hospital::findOrFail(1);

        // 通常手数料
        $feeRates = FeeRate::where('hospital_id', 1)->where('type', 0)->get();

        // 事前決済手数料
        $prePaymentFeeRates = FeeRate::where('hospital_id', 1)->where('type', 1)->get();
        

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
     * @return 医療機関一覧画面
     */
    public function store(Request $request)
    {
        try {
            $this->saveAttentionInformation($request);
            $request->session()->flash('success', trans('messages.created', ['name' => trans('messages.names.attetion_information')]));
            return redirect('hospital');
        } catch (Exception $e) {
            return redirect()->back()->withErrors(trans('messages.create_error'))->withInput();
        }
    }

    /**
     * 医療機関こだわり情報と手数料を保存する
     * メソッドが大きくなるので、分割した
     * 
     * @param Request 医療機関こだわり情報, 手数料
     * @return 
     */
    protected function saveAttentionInformation(Request $request)
    {
        $this->validate($request, [
            'pvad' => 'digits_between:1,10'
        ]);
        
        // @todo feeRate from のバリデーション

        try {
            DB::beginTransaction();

            // 通常手数料
            $fee_rate_ids = collect($request->input('fee_rate_ids'), []);
            $rates = collect($request->input('rates'), []);
            $from_dates = collect($request->input('from_dates'), []);

            if($fee_rate_ids->isNotEmpty()) {
                foreach ($fee_rate_ids as $index => $fee_rate_id) {
                    if ($fee_rate_id) {
                        $fee_rate = FeeRate::findOrFail($fee_rate_id);
                        $fee_rate->rate = $rates[$index];
                        $fee_rate->from_date = $from_dates[$index];
                        $fee_rate->save();
                    } else {
                        $fee_rate = new FeeRate([
                            // @todo 医療機関IDを入れる
                            'hospital_id' => 1,
                            'type' => 0,
                            'rate' => $rates[$index],
                            'from_date' => $from_dates[$index],

                            // @todo date_toの入力
                            // 'to_date' =>
                        ]);
                        $fee_rate->save();
                    }
                }
            }

            // 事前決済手数料
            $pre_payment_fee_rate_ids = collect($request->input('pre_payment_fee_rate_ids'), []);
            $pre_payment_rates = collect($request->input('pre_payment_rates'), []);
            $pre_payment_from_dates = collect($request->input('pre_payment_from_dates'), []);

            if($pre_payment_fee_rate_ids->isNotEmpty()) {
                foreach ($pre_payment_fee_rate_ids as $index => $pre_payment_fee_rate_id) {
                    if ($pre_payment_fee_rate_id) {
                        $fee_rate = FeeRate::findOrFail($pre_payment_fee_rate_id);
                        $fee_rate->rate = $pre_payment_rates[$index];
                        $fee_rate->from_date = $pre_payment_from_dates[$index];
                        $fee_rate->save();
                    } else {
                        $fee_rate = new FeeRate([
                            // @todo 医療機関IDを入れる
                            'hospital_id' => 1,
                            'type' => 1,
                            'rate' => $pre_payment_rates[$index],
                            'from_date' => $pre_payment_from_dates[$index],

                            // @todo date_toの入力
                            // 'to_date' =>
                        ]);
                        $fee_rate->save();
                    }
                }
            }

            $hospital = Hospital::findOrFail(1);
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
                    } else {
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
    }
}
