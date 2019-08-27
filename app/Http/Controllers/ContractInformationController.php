<?php

namespace App\Http\Controllers;

use App\ContractInformation;
use App\Hospital;
use App\HospitalStaff;
use Illuminate\Http\Request;
use App\Http\Requests\ContractInformationFormRequest;
use Illuminate\Support\Facades\DB;

class ContractInformationController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('hospital.create-contract-form');
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
	public function show($hospital_id)
	{

		$hospital = Hospital::findOrFail($hospital_id);
		$contractInformation = ContractInformation::where('hospital_id', $hospital->id)->first();

		return view('hospital.show-contract-information', ['hospital' => $hospital, 'contractInformation' => $contractInformation, 'tab=hospital-information']);
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
}
