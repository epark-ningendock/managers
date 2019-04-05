<?php

namespace App\Http\Requests;

use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\In;

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
        $login_id = 'required|min:6|alpha_num|unique:staffs' . $is_edit ? ',login_id,' . $this->staff->id : '';
        $rules = [
            'name' => 'required',
            'login_id' => $login_id,
            'email' => 'required|email',
            'status' => 'required|enum_value:' . Status::class . ',false',
            'is_hospital' => ['required', Rule::in([0, 1, 3])],
            'is_staff' => ['required', Rule::in([0, 1, 3])],
            'is_item_category' => ['required', Rule::in([0, 1, 3, 7])],
            'is_invoice' => ['required', Rule::in([0, 1, 3, 7])],
            'is_pre_account' => ['required', Rule::in([0, 1, 3, 7])]
        ];

        if (!$is_edit) {
            $rules->merge([
                'password' => 'required|min:6',
                'password_confirmation' => 'required|min:6|same:password',
            ]);
        }

        return rules;
    }
}
