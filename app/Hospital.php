<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    protected $table = 'hospitals';

    //Note $fillable is temporary for factory, make it realistic field when business logic
    protected $fillable = [
        'karada_dog_id',
        'code',
        'old_karada_dog_id',
        'name',
        'kana',
        'zip_code',
        'pref',
        'district_code_id',
        'address1',
        'address2',
        'longitude',
        'latitude',
        'direction',
        'streetview_url',
        'tel',
        'paycall',
        'fax',
        'email',
        'url',
        'consultation_note',
        'memo',
        'business_hours',
        'rail1',
        'station1',
        'access1',
        'rail2',
        'station2',
        'access2',
        'rail3',
        'station3',
        'access3',
        'rail4',
        'station4',
        'access4',
        'rail5',
        'station5',
        'access5',
        'memo1',
        'memo2',
        'memo3',
        'principal',
        'principal_history',
        'pv_count',
        'pvad',
        'is_pickup',
        'hospital_staff_id',
        'login_id',
        'login_psw',
        'login_status',
        'status',
        'certified_facility',
        'free_area',
        'search_word',
        'plan_code',
        'hplink_contract_type',
        'hplink_count',
        'hplink_price',
        'created_id',
        'update_id',
        'pre_account_flg',
        'pre_account_discount_rate',
        'pre_account_commission_rate',
    ];

    /**
     * 医療機関に関連する受付メール設定レコードを取得
     */
    public function reception_email_setting()
    {
        return $this->hasOne('App\ReceptionEmailSetting');
    }
}
