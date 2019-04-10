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

		return redirect( 'hospital-staff' )->with( 'status', '新しい施設職員が創設される' );

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

		return redirect( 'hospital-staff' )->with( 'status', 'データ更新' );

	}

	public function destroy( $id ) {

		$hospital_staff = HospitalStaff::findOrFail( $id );
		$hospital_staff->destroy( $id );

		return redirect( 'hospital-staff' )->with( 'success', 'データが削除されました' );
	}
}
