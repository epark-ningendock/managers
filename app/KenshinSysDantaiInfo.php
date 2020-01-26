<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Reshadman\OptimisticLocking\OptimisticLocking;

class KenshinSysDantaiInfo extends SoftDeleteModel
{
    use SoftDeletes;

    protected $dates = [
        'deleted_at'];

    protected $fillable = [
        'kenshin_sys_hospital_id',
        'kenshin_sys_dantai_no',
        'kenshin_sys_dantai_nm',
        'created_at',
        'updated_at'
    ];

    public function kenshin_sys_courses()
    {
        return $this->hasMany(KenshinSysCourse::class);
    }

    public function kenshin_sys_hoken_infos()
    {
        return $this->hasMany(KenshinSysHokenInfo::class);
    }
}
