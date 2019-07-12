<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationCreateFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'regular_price' => 'max:8',
            'adjustment_price' => 'max:8',
            'start_time_hour' => 'between:0,23',
            'start_time_min' => 'between:0,23',
            'reservation_memo' => 'max:255',
            'family_name' => 'max:32',
            'first_name' => 'max:32',
            'family_name_kana' => 'max:32',
            'first_name_kana' => 'max:32',
            'tel' => 'max:11',
            'registration_card_number' => 'max:32',
        ];
    }
}
