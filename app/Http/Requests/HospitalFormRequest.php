<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\HospitalEnums;

class HospitalFormRequest extends FormRequest
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
        $status = HospitalEnums::getValues();

        return [
            'status' => Rule::in($status),
        ];
    }

    public function messages()
    {
        return [
            'status.in' => '「状態」を正しく選択してください。',
        ];
    }
}
