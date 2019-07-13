<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class LoginFormRequest extends FormRequest
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
        $rules = [
          'login_id' => 'required|between:8,50|regex:/^[-_ @\. a-zA-Z0-9]+$/',
          'password' => 'required|between:8,20|regex:/^[a-zA-Z0-9]+$/'
        ];
        return $rules;
    }
}
