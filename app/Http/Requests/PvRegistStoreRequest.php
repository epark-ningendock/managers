<?php

namespace App\Http\Requests;


class PvRegistStoreRequest extends ValidationRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'hospital_no' => ['required','numeric','exists:hospitals,id'],
        ];

    }

}