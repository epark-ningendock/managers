<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class EmailReceptionFlg implements Rule
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
        if (request('email_reception_flg') == '1'
            && (request('in_hospital_reception_email_flg') != '1'
            && request('web_reception_email_flg') != '1')) {
            return false;
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
        return '受付メール受信アドレス設定を受け取る場合は、1つ以上指定してください。';
    }
}
