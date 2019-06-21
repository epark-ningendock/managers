<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Classification;
use App\Enums\Status;
use Illuminate\Validation\Rule;

class ClassificationSearchFormRequest extends FormRequest
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
        $classification = Classification::getValues();
        $status = Status::getValues();
        return [
            'type' => 'nullable|int',
            'classification' => Rule::in($classification),
            'major' => 'nullable|int',
            'middle' => 'nullable|int',
            'status' => Rule::in($status),
        ];
    }

    public function messages()
    {
        return [
            'type.integer' => '「分類種別」を正しく選択してください。',
            'classification.in' => '「分類」を正しく選択してください。',
            'major.integer' => '「大分類」を正しく選択してください。',
            'middle.integer' => '「中分類」を正しく選択してください。',
            'status.in' => '「状態」を正しく選択してください。。',
        ];
    }
}
