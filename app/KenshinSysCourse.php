<?php

namespace App;

use App\Helpers\EnumTrait;
use Reshadman\OptimisticLocking\OptimisticLocking;

class KenshinSysCourse extends SoftDeleteModel
{
    use EnumTrait, OptimisticLocking;

    protected $fillable = [
        'kenshin_sys_hospital_id',
        'kenshin_sys_dantai_no',
        'kenshin_sys_course_no',
        'kenshin_sys_course_name',
        'kenshin_sys_course_kingaku',
        'kenshin_sys_riyou_bgn_date',
        'kenshin_sys_riyou_end_date',
        'kenshin_sys_course_age_kisan_kbn',
        'kenshin_sys_course_age_kisan_date'
    ];

    protected $dates = [
        'kenshin_sys_riyou_bgn_date',
        'kenshin_sys_riyou_end_date',
        'kenshin_sys_course_age_kisan_date'
    ];

    public function kenshin_sys_dantai_infos()
    {
        return $this->belongsTo('App\KenshinSysDantaiInfo', ['kenshin_sys_hospital_id', 'kenshin_sys_dantai_no'], ['kenshin_sys_hospital_id', 'kenshin_sys_dantai_no']);
    }

    public function course_futan_conditions()
    {
        return $this->hasMany('App\CourseFutanCondition');
    }

    public function kenshin_sys_course_wakus()
    {
        return $this->hasMany('App\KenshinSysCourseWaku');
    }
}
