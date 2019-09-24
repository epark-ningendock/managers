<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Reshadman\OptimisticLocking\OptimisticLocking;

class Hospital extends Model
{
    use SoftDeletes, OptimisticLocking;
    protected $table = 'hospitals';

    //Note $fillable is temporary for factory, make it realistic field when business logic
    protected $fillable = [
        'old_karada_dog_id',
        'name',
        'kana',
        'postcode',
        'prefecture_id',
        'district_code_id',
	    'medical_examination_system_id',
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
        'lock_version',
        'biography',
        'representative',

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

    public function lock()
    {
        return $this->hasOne('App\Lock');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

}
