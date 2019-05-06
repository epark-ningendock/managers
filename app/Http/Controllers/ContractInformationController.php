<?php

namespace App\Http\Controllers;

use App\ContractInformation;
use App\HospitalStaff;
use Illuminate\Http\Request;
use App\Http\Requests\ContractInformationFormRequest;

class ContractInformationController extends Controller
{
    
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContractInformationFormRequest $request)
    {

	    $hospital_staff           = new HospitalStaff();
	    $hospital_staff->name = $request->medical_institution_name;
	    $hospital_staff->email = $request->email;
	    $hospital_staff->password = bcrypt($request->password);
	    $hospital_staff->login_id = $request->login;
	    $hospital_staff->save();

	    $data = $request->all();
	    $data['hospital_staff_id']  = $hospital_staff->id;

	    return ContractInformation::create($data);
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
}
