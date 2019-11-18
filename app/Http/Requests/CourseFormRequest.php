<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\WebReception;

class CourseFormRequest extends FormRequest
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
        if (str_contains($this->url(), 'course/sort/update')) {
            return [
                'course_ids' => 'required|array',
                'course_ids.*' => 'sometimes|integer'
            ];
        } else {
            return [
                'name' => 'required|max:64',
                'web_reception' => 'required|enum_value:' . WebReception::class . ',false',
                'calendar_id' => 'nullable|exists:calendars,id',
                'is_category' => [ 'required', Rule::in([0, 1]) ],
                'reception_start_day' => 'required|integer|min:0',
                'reception_start_month' => 'required|integer|min:0',
                'reception_end_day' => 'required|integer|min:0',
                'reception_end_month' => 'required|integer|min:0',
                //'reception_acceptance_day' => 'required|integer|min:0',
                //'reception_acceptance_month' => 'required|integer|min:0',
                'cancellation_deadline' => 'required|integer|min:0',
                'pre_account_price' => 'nullable|integer',
                'is_price' => [Rule::in([0, 1])],
                'is_price_memo' => [Rule::in([0, 1])],
                'price' => 'required_if:is_price,1|numeric|required_without:price_memo',
                'price_memo' => 'required_if:is_price_memo,1|required_without:price',
                'is_pre_account' => ['required', Rule::in([0, 1])],
                'course_option_ids' => 'array',
                'course_option_ids.*' => 'sometimes|integer',
                'minor_ids' => 'array',
                'minor_values' => 'array',
                'is_questions' => 'required|array',
                'is_questions.*' => [ Rule::in([0, 1]) ],
                'question_titles' => 'array',
                'answer01s' => 'array',
                'answer02s' => 'array',
                'answer03s' => 'array',
                'answer04s' => 'array',
                'answer05s' => 'array',
                'answer06s' => 'array',
                'answer07s' => 'array',
                'answer08s' => 'array',
                'answer09s' => 'array',
                'answer10s' => 'array',
                'publish_start_date' => 'date|nullable',
                'publish_end_date' => 'date|nullable',
                'reception_acceptance_day_end' => 'date|nullable',
            ];
        }
    }

    public function messages()
    {
        return [
            'name.required' => trans('validation.required', [ 'attribute' => trans('validation.attributes.course_name')]),
            'is_category.required' => trans('validation.required', [ 'attribute' => trans('validation.attributes.course_is_category')]),
            'is_pre_account.required' => trans('validation.required', [ 'attribute' => trans('validation.attributes.course_is_pre_account')]),
            'price.required_if' => trans('validation.required', [ 'attribute' => trans('validation.attributes.price')]),
            'price_memo.required_if' => trans('validation.required', [ 'attribute' => trans('validation.attributes.price_memo')]),
        ];
    }
}
