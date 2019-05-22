<?php

namespace App\Http\Requests;

use App\HospitalStaff;
use Illuminate\Foundation\Http\FormRequest;

class HospitalStaffFormRequest extends FormRequest {
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

		$email_validation = ( $this->method() == 'PUT' || $this->method() == 'PATCH' ) ? 'required|unique:hospital_staffs,email,'. $this->hospital_staff : 'required|unique:hospital_staffs|email';
		$login_id         = ( $this->method() == 'PUT' || $this->method() == 'PATCH' ) ? 'required|unique:hospital_staffs,login_id, '. $this->hospital_staff : 'required|unique:hospital_staffs';
		// $password         = ( $this->method() == 'PUT' || $this->method() == 'PATCH' ) ? '' : 'required|min:6';

		return [
			'name'     => 'required',
			'email'    => $email_validation,
			'login_id' => $login_id,
			// 'password' => $password,
		];
	}
}
