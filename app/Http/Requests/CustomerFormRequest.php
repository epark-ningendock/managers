<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
//            'name_kana' => 'required',
//            'tel' => 'required',
            'sex' => 'required',
//            'birthday' => 'required',
//            'postcode' => 'required',
//            'prefecture_id' => 'required',
//            'city_or_country' => 'required',
//            'address' => 'required',
//            'email' => 'required|email',
//            'memo' => 'required',
//            'reservation_memo' => 'required',
            'claim_count' => 'required|integer',
            'recall_count' => 'required|integer',
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
        ];
    }
}
