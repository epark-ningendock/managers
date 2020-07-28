<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\CalendarDisplay;
use Illuminate\Validation\Rule;

class CalendarFormRequest extends FormRequest
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
        if (str_contains($this->url(), 'setting')) {
            return [
                'days' => 'required|array',
                'days.*' => 'integer',
                'is_reservation_acceptances' => 'required|array',
                'is_reservation_acceptances.*' => [Rule::in(['0', '1'])],
                'reservation_frames' => 'required|array',
                'reservation_frames.*' => 'nullable|integer'
            ];
        } elseif (str_contains($this->url(), 'holiday')) {
            return [
                'days' => 'required|array',
                'days.*' => 'integer',
                'is_holidays' => 'required|array',
                'is_holidays.*' => [ Rule::in(['0', '1'])]
            ];
        } else {
            return [
                'name' => 'required',
                'is_calendar_display' => 'required|enum_value:' . CalendarDisplay::class . ',false',
                'unregistered_course_ids' => 'array',
                'registered_course_ids' => 'array',
                'unregistered_course_ids.*' => 'integer',
                'registered_course_ids.*' => 'integer',
                'auto_update_start_date' => 'date|nullable',
                'auto_update_end_date' => 'date|nullable'
            ];
        }
    }

    public function messages()
    {
        return [
            'name.required' => trans('validation.required', [ 'attribute' => trans('validation.attributes.calendar_name')])
        ];
    }
}
