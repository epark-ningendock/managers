<?php

namespace App\Http\Requests;

use App\HospitalStaff;
use Illuminate\Foundation\Http\FormRequest;

class HospitalStaffFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $email_validation = ($this->method() == 'PUT' || $this->method() == 'PATCH') ? 'required|unique:hospital_staffs,email,'. $this->hospital_staff : 'required|unique:hospital_staffs|email';
        $login_id         = ($this->method() == 'PUT' || $this->method() == 'PATCH') ? 'required|between:8,50|regex:/^[-_ @\.a-zA-Z0-9]+$/|unique:hospital_staffs,login_id, '. $this->hospital_staff : 'required|between:8,50|regex:/^[-_ @\.a-zA-Z0-9]+$/|unique:hospital_staffs';

        return [
            'name'     => 'required|between:1,25',
            'email'    => $email_validation,
            'login_id' => $login_id,
            'password' => 'min:8|max:20|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:8|max:20',
        ];
    }
}
