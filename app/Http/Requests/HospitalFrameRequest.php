<?php

namespace App\Http\Requests;


class HospitalFrameRequest extends ValidationRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'hospital_code' => ['required','regex:/^D[0-9a-zA-Z]+$/u','exists:contract_informations,code'],
            'get_yyyymmdd_from' => ['required','numeric','regex:/^2[0-9]{7}$/u'],
            'get_yyyymmdd_to' => ['required','numeric','regex:/^2[0-9]{7}$/u'],
        ];

    }

}