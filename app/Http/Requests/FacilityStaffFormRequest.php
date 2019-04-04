<?php

namespace App\Http\Requests;

use App\FacilityStaff;
use Illuminate\Foundation\Http\FormRequest;

class FacilityStaffFormRequest extends FormRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {

		$email_validation = ( $this->method() == 'PUT' || $this->method() == 'PATCH' ) ? 'required|unique:facility_staffs,email,'. $this->facility_staff : 'required|unique:facility_staffs|email';
		$login_id         = ( $this->method() == 'PUT' || $this->method() == 'PATCH' ) ? 'required|unique:facility_staffs,login_id, '. $this->facility_staff : 'required|unique:facility_staffs';
		$password         = ( $this->method() == 'PUT' || $this->method() == 'PATCH' ) ? '' : 'required|min:6';

		return [
			'name'     => 'required',
			'email'    => $email_validation,
			'login_id' => $login_id,
			'password' => $password,
		];
	}

	/**
	 * Get the error messages for the defined validation rules.
	 *
	 * @return array
	 */
	public function messages() {
		return [
			'name.required'     => '名前が必要です',
			'email.required'    => 'メールアドレスが必要です',
			'email.unique'      => 'このメールは既に存在します',
			'email.email'       => 'これは電子メールになります',
			'login_id.required' => 'ログインIDが必要です',
			'login_id.unique'   => 'ログインIDはすでに取得されています。',
			'password.required' => 'パスワードIDが必要です',
		];
	}
}
