<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReceptionEmailSetting extends Model
{
    protected $fillable = [
        'in_hospital_email_reception_flg',
        'in_hospital_confirmation_email_reception_flg',
        'in_hospital_change_email_reception_flg',
        'in_hospital_cancellation_email_reception_flg',
        'email_reception_flg',
        'in_hospital_reception_email_flg',
        'web_reception_email_flg',
        'reception_email1',
        'reception_email2',
        'reception_email3',
        'reception_email4',
        'reception_email5',
        'epark_in_hospital_reception_mail_flg',
        'epark_web_eception_email_flg'
    ];
}
