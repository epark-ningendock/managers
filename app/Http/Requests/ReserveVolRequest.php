<?php

namespace App\Http\Requests;

use App\Http\Requests\ValidationRequest;

class ReserveVolRequest extends ValidationRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'hospital_no' => ['required','exists:hospitals,id'],
        ];

    }

}