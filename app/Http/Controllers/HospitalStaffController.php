<?php

namespace App\Http\Controllers;

use App\HospitalStaff;
use App\Http\Requests\HospitalStaffFormRequest;
use Illuminate\Http\Request;

class HospitalStaffController extends Controller
{
	public function index() {
		return view( 'hospital_staff.index', [ 'hospital_staffs' => HospitalStaff::paginate( 20 ) ] );
	}

	public function create() {
		return view( 'hospital_staff.create' );
	}

	public function store( HospitalStaffFormRequest $request ) {

		$hospital_staff           = new HospitalStaff( $request->all() );
		$hospital_staff->password = bcrypt( $request->input( 'password' ) );
		$hospital_staff->save();

		return redirect( 'hospital-staff' )->with( 'success', trans('messages.created', ['name' => trans('messages.names.hospital_staff')]) );

	}

	public function edit( $id ) {

		$hospital_staff = HospitalStaff::findOrFail( $id );

		return view( 'hospital_staff.edit', compact( 'hospital_staff' ) );
	}

	public function update( HospitalStaffFormRequest $request, $id ) {

		$hospital_staff     = HospitalStaff::findOrFail( $id );
		$inputs             = request()->all();
		$inputs['password'] = bcrypt( $request->input( 'password' ) );
		$hospital_staff->update( $inputs );

		return redirect( 'hospital-staff' )->with( 'success', trans('messages.updated', ['name' => trans('messages.names.hospital_staff')]) );

	}

	public function destroy( $id ) {

		$hospital_staff = HospitalStaff::findOrFail( $id );
		$hospital_staff->delete();

		return redirect( 'hospital-staff' )->with( 'success', trans('messages.deleted', ['name' => trans('messages.names.hospital_staff')]) );
	}
}
