<?php

namespace App\Http\Controllers;

use App\ContractInformation;
use App\ContractPlan;
use App\Hospital;
use App\HospitalStaff;
use Illuminate\Http\Request;
use App\Http\Requests\ContractInformationFormRequest;
use Illuminate\Support\Facades\DB;
use App\Filters\ContractInformation\ContractInformationFilters;
use \Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class HospitalContractController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ContractInformationFilters $filter) {
        $contract_informations = ContractInformation::with('hospital')
            ->filter($filter)->orderBy('updated_at', 'DESC')->paginate(20);

        return view('contract-information.index', [ 'contract_informations' => $contract_informations ])
            ->with(request()->input());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($hospital_id)
    {
        $hospital = Hospital::findOrFail($hospital_id);
        return view('hospital.create-contract')
            ->with('hospital', $hospital);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // TODO この関数は全体的に見直し必要
    public function store(ContractInformationFormRequest $request)
    {
        try {
            // 医療機関、医療機関スタッフ、契約情報を作成
            DB::beginTransaction();
            // 医療機関を登録（事前決済値引率がNot Null制約が入っているが、実際に登録出来ないので検討）
            // $hospital_data = $request->only(['hospital_name_kana', 'hospital_name']);
            // $hospital = new Hospital($hospital_data);
            // $hospital->save();

            // 医療機関スタッフ（代表者）を作成
            $hospital_staff_data = $request->only(['email', 'login_id', 'password']);
            $hospital_staff_data['password'] = bcrypt($hospital_staff_data['password']);
            $hospital_staff = new HospitalStaff($hospital_staff_data);
            $hospital_staff->name = $request->get('representative_name');
            // TODO 同時に医療機関も作成しないとならない
            $hospital_staff->hospital_id = 3;
            $hospital_staff->save();

            // 契約情報レコードを作成する
            $contract_information_data = $request->only(['contractor_name_kana', 'contractor_name', 'application_date', 'billing_start_date', 'cancellation_date', 'representative_name_kana', 'representative_name', 'postcode', 'address', 'tel', 'fax', 'old_karada_dog_id', 'karada_dog_id']);
            $contract_information = new ContractInformation($contract_information_data);
            // TODO 医療機関スタッフIDではなく、医療機関IDに変更する
            $contract_information->code = sprintf('D%06d', $hospital_staff->id);
            $contract_information->hospital_staff_id = $hospital_staff->id;
            $contract_information->save();

            // $request->session()->flash('success', trans('messages.created', ['name' => trans('messages.names.staff')]));
            DB::commit();
            return redirect('hospital');
        } catch (\Exception $e) {
            DB::rollback();
            // return redirect('course');
            return redirect()->back();
            // return redirect()->back()->withErrors(trans('messages.staff_create_error'))->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ContractInformation  $contractInformation
     * @return \Illuminate\Http\Response
     */
    public function show(ContractInformation $contractInformation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ContractInformation  $contractInformation
     * @return \Illuminate\Http\Response
     */
    public function edit(ContractInformation $contractInformation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ContractInformation  $contractInformation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ContractInformation $contractInformation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ContractInformation  $contractInformation
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContractInformation $contractInformation)
    {
        //
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
        $row = fgetcsv($file, 0, "\t");

        $contract_arr = [
                            'property_no' => trimToNull($row[1]),
                            'code' => "$row[2]",
                            'contractor_name_kana' => $row[3],
                            'contractor_name' => $row[4],
                            'representative_name_kana' => $row[5],
                            'representative_name' => $row[6],
                            'postcode' => $row[7],
                            'address' => trimToNull(join(array_slice($row, 8, 4), ' ')),
                            'tel' => trimToNull($row[12]),
                            'fax' => trimToNull($row[13]),
                            'email' => trimToNull($row[14]),
                            'application_date' => trimToNull($row[15]),
                            'cancellation_date' => trimToNull($row[16]),
                            'billing_start_date' => trimToNull($row[17]),
                            'plan_code' => $row[18],
                            'service_start_date' => trimToNull($row[19]),
                            'service_end_date' => trimToNull($row[20]),
                            'hospital_name' => $row[21],
                            'hospital_name_kana' => $row[22]
                        ];

        $validator = Validator::make($contract_arr, $this->rules(), $this->messages());


        if($validator->fails()) {
            Session::flash('error', $validator->messages()->first());
            return redirect()->back();
        }

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


        $contract = ContractInformation::where('property_no', $contract_arr['property_no'])->get()->first();

        $contract_plan = ContractPlan::where('plan_code', $contract_arr['plan_code'])->get()->first();

        if (!isset($contract)) {
            $contract = new ContractInformation($contract_arr);
        } else {
            $contract->fill($contract_arr);
            $contract->hospital->name = $contract_arr['hospital_name'];
            $contract->hospital->kana = $contract_arr['hospital_name_kana'];
        }


        return view('contract-information.upload-confirm', ['contract' => $contract, 'contract_plan' => $contract_plan ]);
    }

    public function storeUpload(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate($this->rules(), $this->messages());

            $contract_plan = ContractPlan::where('plan_code', $request->plan_code)->get()->first();

            $hospital = new Hospital();
            $hospital->name = $request->hospital_name;
            $hospital->kana = $request->hospital_name_kana;
            $hospital->save();

            $contract = ContractInformation::where('property_no', $request->property_no)->get()->first();

            if (!isset($contract)) {
                $contract = new ContractInformation();
            }

            $contract_arr = $request->except(['hospital_name', 'hospital_name_kana', 'plan_code']);

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

            $contract->fill($contract_arr);
            $contract->contract_plan_id = $contract_plan->id;
            $contract->hospital_id = $hospital->id;
            $contract->save();

            DB::commit();

            return redirect('/contract')->with('success', trans('messages.contract_saved') );
        } catch(ValidationException $e) {
            DB::rollback();
            throw $e;
        } catch (\Exception $e) {
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
            'property_no' => 'required|max:20',
            'code' => 'required|max:20',
            'contractor_name_kana' => 'required|max:50',
            'contractor_name' => 'required|max:50',
            'representative_name_kana' => 'required|max:50',
            'representative_name' => 'required|max:50',
            'postcode' => 'nullable|regex:/^\d{3}-?\d{4}$/',
            'address' => 'nullable|max:200',
            'tel' => 'required|digits_between:8, 11',
            'fax' => 'nullable|digits_between:8, 11',
            'email' => 'nullable|email',
            'application_date' => 'required|date_format:Y/m/d',
            'cancellation_date' => 'nullable|date_format:Y/m/d',
            'billing_start_date' => 'required|date_format:Y/m/d',
            'plan_code' => 'required|max:2|exists:contract_plans,plan_code',
            'service_start_date' => 'nullable|date_format:Y/m/d',
            'service_end_date' => 'nullable|date_format:Y/m/d',
            'hospital_name' => 'required|max:50',
            'hospital_name_kana' => 'required|max:50'
        ];
    }

    /**
     * custom validation message for contract information validation
     * @return array
     */
    protected function messages()
    {
        return [
            'postcode.regex' => trans('validation.invalid', ['attribute' => trans('validation.attributes.postcode') ])
        ];
    }
}
