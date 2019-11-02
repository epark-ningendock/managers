<?php

namespace App\Http\Requests;

class ReservationShowRequest extends ReservationRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return parent::rules() + [
            'reservation_id' => 'required',
        ];
    }
}
