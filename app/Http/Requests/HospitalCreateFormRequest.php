<?php

namespace App\Http\Requests;

use App\Enums\HospitalEnums;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HospitalCreateFormRequest extends FormRequest
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
}
