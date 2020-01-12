<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Reshadman\OptimisticLocking\OptimisticLocking;

class KenshinSysOption extends SoftDeleteModel
{
    use SoftDeletes, OptimisticLocking;

    protected $dates = [
        'kenshin_sys_option_age_kisan_date',
        'deleted_at'];

    protected $fillable = [
        'kenshin_sys_hospital_id',
        'kenshin_sys_dantai_no',
        'kenshin_sys_course_no',
        'kenshin_sys_option_no',
        'kenshin_sys_option_name',
        'kenshin_sys_option_age_kisan_kbn',
        'kenshin_sys_option_age_kisan_date',
        'created_at',
        'updated_at'
    ];

    public function option_futan_conditions()
    {
        return $this->hasMany(OptionFutanCondition::class);
    }
}
