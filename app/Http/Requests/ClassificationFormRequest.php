<?php

namespace App\Http\Requests;

use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClassificationFormRequest extends FormRequest
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
        if (str_contains($this->url(), 'classification/sort/update')) {
            return [
                'classification' => ['required', Rule::in(['major', 'middle', 'minor'])],
                'classification_ids' => 'required|array',
                'classification_ids.*' => 'sometimes|integer'
            ];
        } else {
            $rules = [
                'classification' => ['required', Rule::in(['major', 'middle', 'minor'])],
                'name' => 'required|max:100',
                'status' => 'required|enum_value:' . Status::class . ',false'
            ];

            $type = $this->input('classification');

            if ($type == 'major') {
                if ($this->method() == 'POST') {
                    $rules['classification_type_id'] = 'required|exists:classification_types,id';
                }
            } else {
                $rules['is_icon'] = ['required', Rule::in(['0', '1'])];

                if ($this->method() == 'POST') {
                    $rules['major_classification_id'] = 'required|exists:major_classifications,id';
                }

                if ($this->input('is_icon') == '1') {
                    $rules['icon_name'] = 'required|max:100';
                }

                if ($type == 'minor') {
                    if ($this->method() == 'POST') {
                        $rules['middle_classification_id'] = 'required|exists:middle_classifications,id';
                    }
                    $rules['is_fregist'] = ['required', Rule::in(['0', '1'])];
                    if ($this->input('is_fregist') == '0') {
                        $rules['max_length'] = 'required|integer|between:1,9999';
                    }
                }
            }
            return $rules;
        }
    }

    public function messages()
    {
        return [
            'name.required' => trans('validation.required', [ 'attribute' => trans('validation.attributes.classification_name')]),
            'name.max' => trans('validation.max.string', [ 'attribute' => trans('validation.attributes.classification_name')])
        ];
    }
}
