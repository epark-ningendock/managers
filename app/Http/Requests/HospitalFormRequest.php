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
            'status' => ['required', Rule::in($status)],
            'latitude' => 'longitude_latitude',
            'longitude' => 'longitude_latitude',
            'kana' => 'max:50',
            'name' => 'max:50',
            'postcode' => 'number_dash',
            'address1' => 'max:256',
            'address2' => 'max:256',
            'tel' => 'number_dash',
            'paycall' => 'number_dash',
            'consultation_note' => 'max:256',
//            'medical_treatment_time.[1].start' => 'date_format:H:i',
//            'medical_treatment_time[1][start]' => 'date_format:H:i',
//            'medical_treatment_time[1][end]' => 'date_format:H:i',
        ];
    }

    public function messages()
    {
        return [
            'status.in' => '「状態」を正しく選択してください。',
        ];
    }
}
