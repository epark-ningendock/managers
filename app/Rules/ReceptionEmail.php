<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ReceptionEmail implements Rule
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
        if ((request('in_hospital_email_reception_flg') == '1'
          || request('email_reception_flg') == '1')
          && (request('reception_email1') == ''
          && request('reception_email2') == ''
          && request('reception_email3') == ''
          && request('reception_email4') == ''
          && request('reception_email5') == '')) {
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
        return '受信メールアドレスを1つ以上入力してください。';
    }
}
