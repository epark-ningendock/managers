<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class BillingEmailFaxNumberCheck implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (  ( request('billing_email_flg') == '1')) {

            $emails = !is_null(request('billing_email1')) && !is_null(request('billing_email2')) && !is_null(request('billing_email3'));
            $fax_number = !is_null(request('billing_fax_number'));

            return ( $emails || $fax_number );

        } else {
            return true;
        }

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '請求メール受信アドレス1~3 or 請求メール受信fax番号 どちらか入力してください。';
    }
}
