<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class InHospitalEmailReceptionFlg implements Rule
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
        if (request('in_hospital_email_reception_flg') == '1'
          && (request('in_hospital_confirmation_email_reception_flg') != '1'
          && request('in_hospital_change_email_reception_flg') != '1'
          && request('in_hospital_cancellation_email_reception_flg') != '1')) {
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
        return '院内受付メール送信設定を希望する場合は、1つ以上指定してください。';
    }
}
