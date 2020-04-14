<?php

namespace App\Http\Requests;


class HospitalRequest extends ValidationRequest
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
        ];

    }

}