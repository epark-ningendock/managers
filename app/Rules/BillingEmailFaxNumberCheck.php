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

            $emails = !is_null(request('billing_email1'))
                || !is_null(request('billing_email2'))
                || !is_null(request('billing_email3'))
                || !is_null(request('billing_email4'))
                || !is_null(request('billing_fax_number'));

            return ( $emails );

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
        return '受け取るを設定した場合、最低1つのメールアドレスを登録してください。';
    }
}
