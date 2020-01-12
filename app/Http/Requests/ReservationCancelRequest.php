<?php

namespace App\Http\Requests;

class ReservationCancelRequest extends ValidationRequest
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
            'mail_fg' => 'required|numeric',
        ];
    }
}
