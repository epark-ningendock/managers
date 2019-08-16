<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hospital extends Model
{
    use SoftDeletes;
    protected $table = 'hospitals';

    //Note $fillable is temporary for factory, make it realistic field when business logic
    protected $fillable = [
        'name',
        'kana',
        'postcode',
        'pref',
        'district_code_id',
        'course_meta_information_id',
        'address1',
        'address2',
        'longitude',
        'latitude',
        'direction',
        'streetview_url',
        'tel',
        'paycall',
        'fax',
        'url',
        'consultation_note',
        'memo',
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
        'status',
        'free_area',
        'search_word',
        'plan_code',
        'hplink_contract_type',
        'hplink_count',
        'hplink_price',
        'is_pre_account',
        'pre_account_discount_rate',
        'pre_account_commission_rate',
        'created_at',
        'updated_at',
    ];

    /**
     * 医療機関に関連する受付メール設定レコードを取得
     */
    public function hospital_email_setting()
    {
        return $this->hasOne('App\HospitalEmailSetting');
    }

    public function hospital_images()
    {
        return $this->hasMany('App\HospitalImage');
    }

    public function hospital_categories()
    {
        return $this->hasMany('App\HospitalCategory');
    }

    public function hospital_details()
    {
        return $this->hasMany('App\HospitalDetail');
    }

    public function contract_information()
    {
        return $this->hasOne('App\ContractInformation');
    }

}
