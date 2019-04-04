<?php

namespace App\Http\Controllers;

use App\FacilityStaff;
use Illuminate\Http\Request;
use App\Http\Requests\FacilityStaffFormRequest;

class FacilityStaffController extends Controller {
	public function index() {
		return view( 'facility_staff.index', [ 'facility_staffs' => FacilityStaff::paginate( 20 ) ] );
	}

	public function create() {
		return view( 'facility_staff.create' );
	}

	public function store( FacilityStaffFormRequest $request ) {

		$facility_staff           = new FacilityStaff( $request->all() );
		$facility_staff->password = bcrypt( $request->input( 'password' ) );
		$facility_staff->save();

		return redirect( 'facility-staff' )->with( 'status', '新しい施設職員が創設される' );

	}

	public function edit( $id ) {

		$facility_staff = FacilityStaff::findOrFail( $id );

		return view( 'facility_staff.edit', compact( 'facility_staff' ) );
	}

	public function update( FacilityStaffFormRequest $request, $id ) {

		$facility_staff     = FacilityStaff::findOrFail( $id );
		$inputs             = request()->all();
		$inputs['password'] = bcrypt( $request->input( 'password' ) );
		$facility_staff->update( $inputs );

		return redirect( 'facility-staff' )->with( 'status', 'データ更新' );

	}

	public function destroy( $id ) {

		$facility_staff = FacilityStaff::findOrFail( $id );
		$facility_staff->destroy( $id );

		return redirect( 'facility-staff' )->with( 'status', 'データが削除されました' );
	}
}
