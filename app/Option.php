<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Reshadman\OptimisticLocking\OptimisticLocking;

class Option extends SoftDeleteModel
{
    use SoftDeletes, OptimisticLocking;

    protected $dates = [
        'kenshin_sys_option_age_kisan_date',
        'deleted_at'];

    protected $fillable = [
        'hospital_id',
        'name',
        'confirm',
        'price',
        'tax_class_id',
        'order',
        'status',
        'kenshin_sys_course_no',
        'kenshin_sys_option_no',
        'kenshin_sys_option_nm',
        'kenshin_sys_option_age_kisan_kbn',
        'kenshin_sys_option_age_kisan_date',
        'kenshin_sys_flg',
        'lock_version',
        'created_at',
        'updated_at'
    ];

    public function reservation_options()
    {
        return $this->hasMany('App\ReservationOption');
    }

    public function tax_class()
    {
        return $this->belongsTo('App\TaxClass');
    }

    public function option_futan_conditions()
    {
        return $this->hasMany('App\OptionFutanCondition');
    }
}
