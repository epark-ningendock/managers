<?php

namespace App\Http\Controllers;

use App\HospitalStaff;
use App\Mail\HospitalStaff\RegisteredMail;
use App\Mail\HospitalStaff\PasswordResetMail;
use App\Mail\HospitalStaff\PasswordResetConfirmMail;
use App\Http\Requests\HospitalStaffFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
		$password = str_random(8);
		$hospital_staff->password = bcrypt( $password );
		$hospital_staff->save();

		$hospital_staff_information = [
			'hospital_staff' => $hospital_staff,
			'password' => $password
		];
		
		// 登録メールを送信する
		Mail::to( $hospital_staff->email )
			->send(new RegisteredMail( $hospital_staff ));
		
		return redirect( 'hospital-staff' )->with( 'success', trans('messages.created', ['name' => trans('messages.names.hospital_staff')]) );

	}

	public function edit( $id ) {

		$hospital_staff = HospitalStaff::findOrFail( $id );

		return view( 'hospital_staff.edit', compact( 'hospital_staff' ) );
	}

	public function update( HospitalStaffFormRequest $request, $id ) {

		$hospital_staff     = HospitalStaff::findOrFail( $id );
		$inputs             = request()->all();
		// $inputs['password'] = bcrypt( $request->input( 'password' ) );
		$hospital_staff->update( $inputs );

		return redirect( 'hospital-staff' )->with( 'success', trans('messages.updated', ['name' => trans('messages.names.hospital_staff')]) );

	}

	public function destroy( $id ) {

		$hospital_staff = HospitalStaff::findOrFail( $id );
		$hospital_staff->delete();

		return redirect( 'hospital-staff' )->with( 'success', trans('messages.deleted', ['name' => trans('messages.names.hospital_staff')]) );
	}

	// ログインユーザーのパスワードの編集画面に遷移する
	public function editPassword() {

		// ログインユーザーのidはログイン時のセッション情報から取得する
		$hospital_staff     = HospitalStaff::findOrFail( 1 );

		return view( 'hospital_staff.edit-password', compact( 'hospital_staff' ) );
	}

	// ログインユーザーのパスワードをUpdateする
	public function updatePassword( $hospital_staff_id, Request $request ) {

		$this->validate($request, [
			'old_password' => 'required',
			'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
			'password_confirmation' => 'min:6'
		]);

		$hospital_staff = HospitalStaff::findOrFail($hospital_staff_id);

		if (Hash::check($request->old_password, $hospital_staff->password)) {
			$hospital_staff->password = bcrypt($request->password);
			$hospital_staff->save();
			return redirect( 'hospital-staff' )->with( 'success', trans('messages.updated', ['name' => trans('messages.names.hospital_staff')]) );
		} else {
			$validator = Validator::make([], []);
			$validator->errors()->add('old_password', '現在のパスワードが正しくありません');
			throw new ValidationException($validator);
			return redirect()->back();
		}

	}

	// パスワードリセットメール送信画面に遷移する
	public function showPasswordResetsMail() {
		return view( 'hospital_staff.send-password-reset-mail' );
	}

	// パスワードリセットメールを送信する
	public function sendPasswordResetsMail() {
		// パスワードリセットメールを送信する
		Mail::to($hospital_staff->email)
			->send(new PasswordResetMail());
	}

	// パスワードリセット画面に遷移する
	public function showResetPassword() {
		return view( 'hospital_staff.reset-password' );
	}

	// パスワードをUpdateする
	public function resetPassword() {
		// 更新完了メールを送信する
		Mail::to( $hospital_staff->email )
			->send(new PasswordResetConfirmMail());
	}
}
