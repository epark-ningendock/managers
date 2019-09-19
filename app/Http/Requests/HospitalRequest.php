<?php

namespace App\Http\Requests;

use App\Http\Requests\ValidationRequest;
use Illuminate\Validation\Rule;

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
            'hospital_code' => 'required|alpha_num|exists:contract_informations,code',
        ];

    }

}