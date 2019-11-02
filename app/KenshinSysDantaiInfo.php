<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Reshadman\OptimisticLocking\OptimisticLocking;

class KenshinSysDantaiInfo extends SoftDeleteModel
{
    use SoftDeletes, OptimisticLocking;

    protected $dates = [
        'deleted_at'];

    protected $fillable = [
        'hospital_id',
        'kenshin_sys_dantai_no',
        'kenshin_sys_dantai_nm',
        'created_at',
        'updated_at'
        ];

    public function courses()
    {
        return $this->hasMany('App\Course');
    }

    public function kenshin_sys_hoken_infos()
    {
        return $this->hasMany('App\KenshinSysHokenInfo');
    }
}
