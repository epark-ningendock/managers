<?php

namespace App\Http\Controllers;

use App\ContractInformation;
use App\ContractPlan;
use App\Hospital;
use App\HospitalStaff;
use App\Mail\Course\HospitalNewRegistMail;
use App\Prefecture;
use Illuminate\Http\Request;
use App\Http\Requests\ContractInformationFormRequest;
use Illuminate\Support\Facades\DB;
use App\Filters\ContractInformation\ContractInformationFilters;
use \Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class HospitalContractInformationController extends Controller
{
    public function __construct(Request $request)
    {
        $this->middleware('authority.level.contract-staff')->except('show');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ContractInformationFilters $filter, Request $request) {
        if(!isset($request->status)) {
            $request->request->add(['status' => 'UNDER_CONTRACT']);
        }

        $contract_informations = ContractInformation::with('hospital')
            ->filter($filter)->orderBy('updated_at', 'DESC')->paginate(20);

        return view('contract-information.index', [ 'contract_informations' => $contract_informations ])
            ->with(request()->input());
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Hospital  $hospital_id
     * @return \Illuminate\Http\Response
     */
	public function show($hospital_id)
	{

		$hospital = Hospital::findOrFail($hospital_id);
		$contract_information = ContractInformation::where('hospital_id', $hospital->id)->first();

		return view('hospital.show-contract-information', ['hospital' => $hospital, 'contract_information' => $contract_information, 'tab=hospital-information']);
    }

    /**
     * Upload contract TSV file
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,tsv,txt'
        ]);

        $file = fopen($request->file('file')->getRealPath(), "r");

        $uploaded_contracts = collect();
        while(($row = fgetcsv($file, 0, "\t")) !== false) {
            $uploaded_contracts->push([
                'property_no' => trimToNull($row[1]),
                'customer_no' => $row[2],
                'code' => null,
                'contractor_name_kana' => $row[3],
                'contractor_name' => $row[4],
                'representative_name_kana' => $row[5],
                'representative_name' => $row[6],
                'postcode' => $row[7],
                'address' => self::convert_prefecture($row[8]) . trimToNull(join(array_slice($row, 9, 3), ' ')),
                'state' => $row[8],
                'county' => $row[9],
                'town' => $row[10],
                'building' => $row[11],
                'tel' => trimToNull($row[12]),
                'fax' => trimToNull($row[13]),
                'email' => trimToNull($row[14]),
                'application_date' => trimToNull($row[16]),
                'cancellation_date' => trimToNull($row[17]),
                'billing_start_date' => trimToNull($row[19]),
                'plan_code' => $row[22],
                'service_start_date' => trimToNull($row[23]),
                'service_end_date' => trimToNull($row[24]),
                'hospital_name' => $row[25]
            ]);
        }
        // skip title
        $uploaded_contracts->forget(0);
        $validator = Validator::make($uploaded_contracts->toArray(), $this->rules(), $this->messages());

        if($validator->fails()) {
            Session::flash('errors', $validator->messages());
            return redirect()->route('contract.index');
        }

        // checking separate field for address
        $validator = Validator::make($uploaded_contracts->toArray(), [
            '*.state' => 'required|max:200',
            '*.town' => 'required|max:200'
        ]);

        if($validator->fails()) {
            Session::flash('errors', $validator->messages());
            return redirect()->route('contract.index');
        }

        $customer_nos = $uploaded_contracts->map(function($contract){
            return $contract['customer_no'];
        });

        $existing_contracts = ContractInformation::whereIn('customer_no', $customer_nos)->get()->groupBy('customer_no');

        $plan_codes = $uploaded_contracts->map(function($contract){
            return $contract['plan_code'];
        });
        $contract_plans = ContractPlan::whereIn('plan_code', $plan_codes)->get()->groupBy('plan_code');

        $contracts = collect();
        foreach ($uploaded_contracts as $contract_arr) {

            // parsing dates
            $contract_arr['application_date'] = Carbon::parse($contract_arr['application_date']);
            $contract_arr['billing_start_date'] = Carbon::parse($contract_arr['billing_start_date']);
            if (!is_null($contract_arr['cancellation_date'])) {
                $contract_arr['cancellation_date'] = Carbon::parse($contract_arr['cancellation_date']);
            }
            if (!is_null($contract_arr['service_start_date'])) {
                $contract_arr['service_start_date'] = Carbon::parse($contract_arr['service_start_date']);
            }
            if (!is_null($contract_arr['service_end_date'])) {
                $contract_arr['service_end_date'] = Carbon::parse($contract_arr['service_end_date']);
            }

            $contract = $existing_contracts->has($contract_arr['customer_no']) ? $existing_contracts->get($contract_arr['customer_no'])->first() : null;
            if(isset($contract)) {
                $contract->fill($contract_arr);
            } else {
                $contract = new ContractInformation($contract_arr);
                $contract->hospital = new Hospital();
            }
            //contract plan change checking
            $contract_plan = $contract_plans->get($contract_arr['plan_code'])->first();
            $contract->is_plan_change = isset($contract->contract_plan) && $contract_plan->id != $contract->contract_plan->id;

            $contract->contract_plan = $contract_plan;
            $contract->hospital->name = $contract_arr['hospital_name'];

            $contracts->push($contract);
        }

        return view('contract-information.upload-confirm',
            [
                'contracts' => $contracts
            ]);
    }

    /**
     * 都道府県名を返す
     * @param $pref_code
     * @return string
     */
    private function convert_prefecture($pref_code) {
        if (empty($pref_code)) {
            return '';
        }

        $pref = Prefecture::find((int) $pref_code);

        return empty($pref) ? '' : $pref->name;
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

             $uploaded_contracts = $request->input('contracts');
             $validator = Validator::make($uploaded_contracts, $this->rules(), $this->messages());


            if($validator->fails()) {
                Session::flash('errors', $validator->messages());
                return redirect()->route('contract.index');
            }

            $property_numbers = collect($uploaded_contracts)->map(function($contract){
                return $contract['customer_no'];
            });

            $existing_contracts = ContractInformation::whereIn('customer_no', $property_numbers)->get()->groupBy('customer_no');

            $plan_codes = collect($uploaded_contracts)->map(function($contract){
                return $contract['plan_code'];
            });
            $contract_plans = ContractPlan::whereIn('plan_code', $plan_codes)->get()->groupBy('plan_code');

            foreach ($uploaded_contracts as $contract_arr) {

                // parsing dates
                $contract_arr['application_date'] = Carbon::parse($contract_arr['application_date']);
                $contract_arr['billing_start_date'] = Carbon::parse($contract_arr['billing_start_date']);
                if (!is_null($contract_arr['cancellation_date'])) {
                    $contract_arr['cancellation_date'] = Carbon::parse($contract_arr['cancellation_date']);
                }
                if (!is_null($contract_arr['service_start_date'])) {
                    $contract_arr['service_start_date'] = Carbon::parse($contract_arr['service_start_date']);
                }
                if (!is_null($contract_arr['service_end_date'])) {
                    $contract_arr['service_end_date'] = Carbon::parse($contract_arr['service_end_date']);
                }

                $contract = $existing_contracts->has($contract_arr['customer_no']) ? $existing_contracts->get($contract_arr['customer_no'])->first() : null;
                $hospital = new Hospital();
                if(isset($contract)) {
                    $contract->fill($contract_arr);
                    $hospital = $contract->hospital;
                } else {
                    $contract = new ContractInformation($contract_arr);
                    $contract->code = 'D2' . $hospital->id;
                }

                $hospital->name = $contract_arr['hospital_name'];
                $hospital->status = '0';
                $hospital->save();

                $contract->contract_plan_id = $contract_plans->get($contract_arr['plan_code'])->first()->id;
                $contract->hospital_id = $hospital->id;
                $contract->save();
            }

            DB::commit();

//            // 完了メール送信
//            $data = [
//                'hospital' => $hospital,
//                'contract_information' => $contract,
//                'subject' => '【EPARK人間ドック】医療機関契約情報登録・更新のお知らせ'
//            ];
//            Mail::to(config('mail.to.gyoumu'))->send(new HospitalNewRegistMail($data));

            return redirect()->route('contract.index')->with('success', trans('messages.contract_saved') );
        } catch(ValidationException $e) {
            DB::rollback();
            throw $e;
        } catch (\Exception $e) {
            Log::error(var_dump($e));
            DB::rollback();
            return redirect()->back()->withErrors(trans('messages.create_error'));
        }
    }


    /**
     * validation rules for contract information
     * @return array
     */
    protected function rules()
    {
        return [
            '*.property_no' => 'required|max:20',
            '*.code' => 'nullable|max:20',
            '*.contractor_name_kana' => 'required|max:50',
            '*.contractor_name' => 'required|max:50',
            '*.representative_name_kana' => 'required|max:50',
            '*.representative_name' => 'required|max:50',
            '*.postcode' => 'required|regex:/^\d{3}-?\d{4}$/',
            '*.address' => 'required|max:200',
            '*.tel' => 'required|regex:/^\d{2,4}-?\d{2,4}-?\d{3,4}$/',
            '*.fax' => 'nullable|regex:/^\d{2,4}-?\d{2,4}-?\d{3,4}$/',
            '*.email' => 'nullable|email',
            '*.application_date' => 'required|date_format:Ymd',
            '*.cancellation_date' => 'nullable|date_format:Ymd',
            '*.billing_start_date' => 'nullable|date_format:Ymd',
            '*.plan_code' => 'required|max:4|exists:contract_plans,plan_code',
            '*.service_start_date' => 'required|date_format:Ymd',
            '*.service_end_date' => 'nullable|date_format:Ymd',
            '*.hospital_name' => 'required|max:50'
        ];
    }

    /**
     * custom validation message for contract information validation
     * @return array
     */
    protected function messages()
    {
        return [
            '*.postcode.regex' => trans('validation.invalid', ['attribute' => trans('validation.attributes.postcode') ])
        ];
    }
}
