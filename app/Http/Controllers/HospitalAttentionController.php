<?php

namespace App\Http\Controllers;

use App\Hospital;
use App\HospitalDetail;
use App\HospitalMiddleClassification;
use App\HospitalMinorClassification;
use App\HospitalPlan;
use App\ContractPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use App\Enums\RegistrationDivision;
use App\Enums\Rate;
use App\Enums\HplinkContractType;

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

        // プラン
        $contractPlans = ContractPlan::all();

        return view('hospital.create-attention')
            ->with('hospital', $hospital)
            ->with('middles', $middles)
            ->with('contractPlans', $contractPlans);
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
    
                $hospital = Hospital::findOrFail($hospital_id);
                $hospital->pvad = $request->get('pvad');
                if ($request->get('is_pickup')) {
                    $hospital->is_pickup = 1;
                } else {
                    $hospital->is_pickup = 0;
                }
                $validator = Validator::make(["free_area" => $request->get('free_area'), "search_word" => $request->get('search_word')], [
                    "free_area" => 'nullable|between:0,2000',
                    "search_word" => 'nullable|between:0,2000',
                ]);
                if ($validator->fails()) {
                    DB::rollback();
                    throw new ValidationException($validator);
                    return redirect()->back();
                }
                $hospital->free_area = $request->get('free_area');
                $hospital->search_word = $request->get('search_word');
                $hospital->hplink_contract_type = $request->get('hplink_contract_type');
                if ($hospital->hplink_contract_type == HplinkContractType::PAY_PER_USE) {
                    $hospital->hplink_count = $request->get('hplink_count');
                    $hospital->hplink_price = $request->get('hplink_price_one');
                } elseif ($hospital->hplink_contract_type == HplinkContractType::MONTHLY_SUBSCRIPTION) {
                    $hospital->hplink_price = $request->get('hplink_price_monthly');
                }
                $hospital->is_pre_account = $request->get('is_pre_account');
                $hospital->save();

                HospitalPlan::updateOrCreate([
                    'hospital_id' => $hospital->id,
                ],
                [
                    'hospital_id' => $hospital->id,
                    'contract_plan_id' => $request->get('contract_plan_id'),
                    'from' => new Carbon('2019-01-01'),
                    'to' => new Carbon('2999-12-31'),
                ]);
    
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

                        if ($input_index == -1 || ($minor->is_fregist == RegistrationDivision::CHECK_BOX && $minor_values[$input_index] == 0)
                            || ($minor->is_fregist == '0' && $minor_values[$input_index] == '')) {
                            continue;
                        }
    
    
                        $hospital_details = new HospitalDetail();
                        $hospital_details->hospital_id = $hospital->id;
                        $hospital_details->minor_classification_id = $minor->id;
                        $minor_id = $minor->id;
                        if ($minor->is_fregist == RegistrationDivision::CHECK_BOX) {
                            $hospital_details->select_status = 1;
                        } else if ($minor->is_fregist == RegistrationDivision::CHECK_BOX_AND_TEXT) {
                            if ($minor_values[$input_index]) {
                                
                                $validator = Validator::make(["minor_id_${minor_id}" => $minor_values[$input_index]], [
                                    "minor_id_${minor_id}" => 'nullable|between:0,10'
                                ]);
                                
                                if ($validator->fails()) {
                                    DB::rollback();
                                    throw new ValidationException($validator);
                                    return redirect()->back();
                                }
                                $hospital_details->select_status = 1;
                                $hospital_details->inputstring = $minor_values[$input_index];  
                            } else {
                                $hospital_details->select_status = 0;
                                $hospital_details->inputstring = '';
                            }
                        }
                         else {
                            $validator = Validator::make(["minor_id_${minor_id}" => $minor_values[$input_index]], [
                                "minor_id_${minor_id}" => 'nullable|between:0,2000'
                            ]);
                    
                            if ($validator->fails()) {
                                DB::rollback();
                                throw new ValidationException($validator);
                                return redirect()->back();
                            }
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
            return redirect()->route('hospital.attention.create', ['hospital_id' => $hospital_id])->with('success', trans('messages.updated', ['name' => trans('messages.names.attetion_information')]));

        } catch (Exception $e) {
            return redirect()->back()->withErrors(trans('messages.create_error'))->withInput();
        }
    }
}
