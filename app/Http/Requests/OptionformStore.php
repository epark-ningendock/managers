<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OptionformStore extends FormRequest
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
        if (request()->route()->uri == 'option/sort/update') {
            return [
                'option_ids' => 'required|array',
                'option_ids.*' => 'sometimes|integer'
            ];
        } else {
            return [
                'name' => 'required|max:40',
                'confirm' => 'max:128',
                'price' => 'required|digits_between:0,8',
                'tax_class_id' => 'required|exists:tax_classes,id',
            ];
        }

    }


    public function messages()
    {
        return [
            'confirm.max' =>  trans('validation.max', ['attribute' => trans('validation.attributes.confirm')])
        ];
    }

    public function attributes()
    {
        $attributes = [
            'name' => 'オプション名'
        ];
        return $attributes;
    }
}
