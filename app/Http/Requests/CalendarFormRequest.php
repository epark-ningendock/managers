<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\CalendarDisplay;

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
        return [
            'name' => 'required',
            'is_calendar_display' => 'required|enum_value:' . CalendarDisplay::class . ',false',
            'unregistered_course_ids' => 'array',
            'registered_course_ids' => 'array',
            'unregistered_course_ids.*' => 'integer',
            'registered_course_ids.*' => 'integer'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => trans('validation.required', [ 'attribute' => trans('validation.attributes.calendar_name')])
        ];
    }
}
