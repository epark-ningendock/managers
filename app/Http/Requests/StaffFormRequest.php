<?php

namespace App\Http\Requests;

use App\Enums\StaffStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\In;
use Illuminate\Validation\Validator;

class StaffFormRequest extends FormRequest
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
        $is_edit = $this->method() == 'PUT' || $this->method() == 'PATCH';
        $login_id = 'required|between:8,50|regex:/^[-_ @\.a-zA-Z0-9]+$/|unique:staffs' . ($is_edit ? ',login_id,' . $this->staff : '');
        $email = 'email|unique:staffs'. ($is_edit ? ',email,' . $this->staff : '');
        $rules = [
            'name' => 'required|between:1,25',
            'login_id' => $login_id,
            'email' => $email,
            'status' => 'required|enum_value:' . StaffStatus::class . ',false',
            'authority' => 'required'
        ];
        return $rules;
    }
}
