<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationUpdateFormRequest extends FormRequest
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
            'adjustment_price' => 'max:8',
            'start_time_hour' => 'between:0,23',
            'start_time_min' => 'between:0,23',
            'reservation_memo' => 'max:255',
            'internal_memo' => 'max:255'
        ];
    }
}
