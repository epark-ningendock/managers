<?php

namespace App\Http\Requests;

use App\EmailTemplate;
use App\Rules\BillingEmailFaxNumberCheck;
use Illuminate\Foundation\Http\FormRequest;

class EmailTemplateFormRequest extends FormRequest
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
            'title' => 'required|max:255',
            'text' => 'max:20000',
            'billing_email_flg' => ['required', new BillingEmailFaxNumberCheck()],
            'billing_email1' => 'nullable|email',
            'billing_email2' => 'nullable|email',
            'billing_email3' => 'nullable|email',
            'billing_fax_number' => 'nullable|digits_between:8,11',
        ];
    }

    public function attributes()
    {
        $attributes = [
            'title' => 'テンプレート名（件名）'
        ];
        return $attributes;
    }

}
