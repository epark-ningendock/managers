<?php

namespace App\Http\Requests;

class ReservationGetAllRequest extends ValidationRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return parent::rules() + [
            'epark_member_id' => 'required',
        ];
    }
}
