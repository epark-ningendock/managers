<?php

namespace App\Http\Requests;

class ReservationCancelRequest extends ReservationRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return parent::rules() + [
            'reservation_id' => 'required|numeric|exists:reservations,id',
            'mail_fg' => 'required|numeric',
        ];
    }
}
