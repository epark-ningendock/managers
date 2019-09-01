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
            'course_id' => 'required',
            'reservation_date' => 'required',
            'regular_price' => 'max:8',
            'adjustment_price' => 'max:8',
            'start_time_hour' => 'between:0,23',
            'start_time_min' => 'between:0,23',
            'reservation_memo' => 'max:255',
            'internal_memo' => 'max:255',
            'family_name' => 'required|max:32',
            'first_name' => 'required|max:32',
            'family_name_kana' => 'required|max:32',
            'first_name_kana' => 'required|max:32',
            'tel' => 'required|regex:/^\d{2,4}-?\d{2,4}-?\d{3,4}$/',
            'registration_card_number' => 'required|max:32',
        ];
    }
}
