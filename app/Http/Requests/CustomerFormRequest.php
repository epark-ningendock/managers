<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Gender;

class CustomerFormRequest extends FormRequest
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
//            'registration_card_number' => 'required',
            'family_name' => 'required',
            'first_name' => 'required',
            'family_name_kana' => ['nullable', 'regex:/^[\p{Hiragana}|ー]+$/u'],
            'first_name_kana' => ['nullable', 'regex:/^[\p{Hiragana}|ー]+$/u'],
            'tel' => 'nullable|regex:/^[0-9]{10,11}$/',
            'sex' => 'required|enum_value:' . Gender::class . ',false',
            'birthday' => 'nullable|date',
            'postcode' => 'nullable|regex:/^\d{3}-?\d{4}$/',
            'prefecture_id' => 'nullable|exists:prefectures,id',
//            'city_or_country' => 'required',
//            'address' => 'required',
            'email' => 'nullable|email',
            'memo' => 'nullable|max:255',
//            'reservation_memo' => 'required',
            'claim_count' => 'nullable|regex:/^[0-9]+$/',
            'recall_count' => 'nullable|regex:/^[0-9]+$/',
        ];
    }

    public function messages()
    {
        return [
            'claim_count.required' => trans('validation.required', ['attribute' => trans('messages.claim_count')]),
            'claim_count.integer' => trans('validation.integer', ['attribute' => trans('messages.claim_count')]),
            'recall_count.required' => trans('validation.required', ['attribute' => trans('messages.recall_count')]),
            'recall_count.integer' => trans('validation.integer', ['attribute' => trans('messages.recall_count')]),
            'sex.required' => trans('validation.required', ['attribute' => trans('messages.gender')]),
            'family_name.required' => trans('validation.required', ['attribute' => trans('messages.family_name')]),
            'first_name.required' => trans('validation.required', ['attribute' => trans('messages.first_name')]),
            'tel.digits_between' => trans('validation.invalid', ['attribute' => trans('validation.attributes.tel')]),
            'postcode.digits' => trans('validation.invalid', ['attribute' => trans('validation.attributes.postcode')])
        ];
    }
}
