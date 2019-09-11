<?php

namespace App\Http\Requests;

use App\Rules\BillingEmailFaxNumberCheck;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\ReceptionEmailSetting;
use App\Rules\OneOfThemWithIf;

class HospitalEmailSettingRequest extends FormRequest
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
            'in_hospital_email_reception_flg' => 'required|enum_value:' . ReceptionEmailSetting::class . ',false',
            'in_hospital_confirmation_email_reception_flg' => 'nullable|enum_value:' . ReceptionEmailSetting::class . ',false',
            'in_hospital_change_email_reception_flg' => 'nullable|enum_value:' . ReceptionEmailSetting::class . ',false',
            'in_hospital_cancellation_email_reception_flg' => 'nullable|enum_value:' . ReceptionEmailSetting::class . ',false',
            'email_reception_flg' => 'required|enum_value:' . ReceptionEmailSetting::class . ',false',
            'in_hospital_reception_email_flg' => 'nullable|enum_value:' . ReceptionEmailSetting::class . ',false',
            'web_reception_email_flg' => 'enum_value:' . ReceptionEmailSetting::class . ',false',
            'reception_email1' => 'nullable|email',
            'reception_email2' => 'nullable|email',
            'reception_email3' => 'nullable|email',
            'reception_email4' => 'nullable|email',
            'reception_email5' => 'nullable|email',
            'epark_in_hospital_reception_mail_flg' => 'nullable|enum_value:' . ReceptionEmailSetting::class . ',false',
            'epark_web_reception_email_flg' => 'nullable|enum_value:' . ReceptionEmailSetting::class . ',false',
            'billing_email_flg' => ['required', new BillingEmailFaxNumberCheck()],
            'billing_email1' => 'nullable|email',
            'billing_email2' => 'nullable|email',
            'billing_email3' => 'nullable|email',
            'billing_fax_number' => 'nullable|regex:/^\d{3}-?\d{4}$/',
        ];
    }

    public function messages()
    {
        return [
            'reception_email1.email' => trans('messages.invalid_email'),
            'reception_email2.email' => trans('messages.invalid_email'),
            'reception_email3.email' => trans('messages.invalid_email'),
            'reception_email4.email' => trans('messages.invalid_email'),
            'reception_email5.email' => trans('messages.invalid_email')
        ];
    }
}
