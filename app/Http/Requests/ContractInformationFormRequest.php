<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContractInformationFormRequest extends FormRequest
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
            'contractor_name_kana' => 'required|max:100',
            'contractor_name' => 'required|max:100',
            'application_date' => 'required|date',
            'billing_start_date' => 'required|date',
            'cancellation_date' => 'required|date|after:today',
            'representative_name_kana' => 'required|max:100',
            'representative_name' => 'required|max:100',
            'postcode' => 'number_dash',
            'address' => 'max:200',
            'tel' => 'required|number_dash',
            'fax' => 'number_dash',
            'email' => 'required|email|unique:hospital_staffs,email',
            'login_id' => 'required|unique:hospitals,login_id',
            'password' => 'required',
            'old_karada_dog_id' => 'start_letter_k',
            'karada_dog_id' => 'required|start_alphabet_and_number'
        ];
    }


    public function attributes()
    {
        return [
            'contractor_name_kana' => trans('messages.names.policy_holder'),
            'representative_name_kana' => trans('messages.names.representative_name_kana'),
            'representative_name' => trans('messages.names.representative_name'),
        ];
    }
}
