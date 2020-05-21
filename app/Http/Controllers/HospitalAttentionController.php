<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Hospital;
use App\HospitalDetail;
use App\HospitalMeta;
use App\HospitalMiddleClassification;
use App\HospitalMinorClassification;
use App\HospitalOptionPlan;
use App\HospitalPlan;
use App\ContractPlan;
use App\OptionPlan;
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

                $dr_movie = $request->get('dr_movie');
                $dr_movie_price = $request->get('dr_movie_price');
                $access_movie = $request->get('access_movie');
                $access_movie_price = $request->get('access_movie_price');
                $one_min_movie = $request->get('one_min_movie');
                $one_min_movie_price = $request->get('one_min_movie_price');
                $tour_movie = $request->get('tour_movie');
                $tour_movie_price = $request->get('tour_movie_price');
                $exam_movie = $request->get('exam_movie');
                $exam_movie_price = $request->get('exam_movie_price');
                $special_page = $request->get('special_page');
                $pay_per_use_price = $request->get('pay_per_use_price');

                if ($dr_movie == 1) {
                    $this->registOptionPlan($hospital_id, $dr_movie_price, $dr_movie);
                } else {
                    $this->deleteOptionPlan($hospital_id, 1);
                }

                if ($access_movie == 2) {
                    $this->registOptionPlan($hospital_id, $access_movie_price, $access_movie);
                } else {
                    $this->deleteOptionPlan($hospital_id, 2);
                }

                if ($one_min_movie == 3) {
                    $this->registOptionPlan($hospital_id, $one_min_movie_price, $one_min_movie);
                } else {
                    $this->deleteOptionPlan($hospital_id, 3);
                }

                if ($tour_movie == 4) {
                    $this->registOptionPlan($hospital_id, $tour_movie_price,  $tour_movie);
                } else {
                    $this->deleteOptionPlan($hospital_id, 4);
                }

                if ($exam_movie == 5) {
                    $this->registOptionPlan($hospital_id, $exam_movie_price,  $exam_movie);
                } else {
                    $this->deleteOptionPlan($hospital_id, 5);
                }

                if ($special_page == 6) {
                    $this->registOptionPlan($hospital_id, 0, $special_page, $pay_per_use_price);
                } else {
                    $this->deleteOptionPlan($hospital_id, 6);
                }
    
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
                                    "minor_id_${minor_id}" => 'nullable|between:0,50'
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

                $this->registHospitalMeta($hospital);
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

    /**
     * @param $hospital
     */
    private function registHospitalMeta($hospital) {

        $hospital_meta = HospitalMeta::where('hospital_id', $hospital->id)->first();
        if (!$hospital_meta) {
            $hospital_meta = new HospitalMeta();
            $hospital_meta->hospital_id = $hospital->id;
        }
        $hospital_details = HospitalDetail::where('hospital_id', $hospital->id)
                                ->get();

        if ($hospital_details) {
            foreach ($hospital_details as $detail) {
                if ($detail->minor_classification_id == 5 and !empty($detail->inputstring)) {
                    $hospital_meta->credit_card_flg = 1;
                }

                if ($detail->minor_classification_id == 1 and $detail->select_status = 1) {
                    $hospital_meta->parking_flg = 1;
                }

                if ($detail->minor_classification_id == 3 and $detail->select_status = 1) {
                    $hospital_meta->pick_up_flg = 1;
                }

                if ($detail->minor_classification_id == 16 and $detail->select_status = 1) {
                    $hospital_meta->children_flg = 1;
                }

                if ($detail->minor_classification_id == 19 and $detail->select_status = 1) {
                    $hospital_meta->dedicate_floor_flg = 1;
                }
            }
            $hospital_meta->save();
        }
    }

    /**
     * @param $hospital_id
     * @param $option_plan_id
     */
    private function registOptionPlan($hospital_id, $price, $option_plan_id, $pay_per_use = null) {
        $hospital_option_plan = HospitalOptionPlan::where('hospital_id', $hospital_id)
            ->where('option_plan_id', $option_plan_id)
            ->first();

        if (!$hospital_option_plan) {
            $hospital_option_plan = new HospitalOptionPlan();
            $hospital_option_plan->from = Carbon::today();
            $hospital_option_plan->hospital_id = $hospital_id;
            $hospital_option_plan->option_plan_id = $option_plan_id;
        }

        $hospital_option_plan->price = $price;
        $hospital_option_plan->pay_per_use_price = $pay_per_use;
        $hospital_option_plan->save();
    }

    /**
     * @param $hospital_id
     * @param $option_plan_id
     */
    private function deleteOptionPlan($hospital_id, $option_plan_id) {
        $hospital_option_plan = HospitalOptionPlan::where('hospital_id', $hospital_id)
            ->where('option_plan_id', $option_plan_id)
            ->first();

        if ($hospital_option_plan) {
            $hospital_option_plan->forceDelete();
        }
    }
}
