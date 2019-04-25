<?php

namespace App\Http\Requests;

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
            return [];
        }

    }

}
