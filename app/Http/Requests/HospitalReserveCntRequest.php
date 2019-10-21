<?php

namespace App\Http\Requests;


class HospitalReserveCntRequest extends ValidationRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'hospital_code' => 'required',
            'hospital_code.*' => 'alpha_num|exists:contract_informations,code'
        ];

    }

}