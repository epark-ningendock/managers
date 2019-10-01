<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Reshadman\OptimisticLocking\OptimisticLocking;

class HospitalEmailSetting extends Model
{
    use OptimisticLocking;

    protected $fillable = [
        'hospital_id',
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
        'epark_web_reception_email_flg',
        'billing_email_flg',
        'billing_email1',
        'billing_email2',
        'billing_email3',
        'billing_fax_number',
        'lock_version'
    ];

    /**
     * この受付メール設定を所有するHospitalを取得
     */
    public function hospital()
    {
        return $this->belongsTo('App\Hospital');
    }
}
