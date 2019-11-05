<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Reshadman\OptimisticLocking\OptimisticLocking;

class KenshinSysHokenInfo extends SoftDeleteModel
{
    use SoftDeletes, OptimisticLocking;

    protected $dates = [
        'deleted_at'];

    protected $fillable = [
        'kenshin_sys_dantai_info_id',
        'hoken_no',
        'hoken_kigou',
        'created_at',
        'updated_at'
        ];

    public function kenshin_sys_dantai_infos()
    {
        return $this->belongsTo('App\KenshinSysDantaiInfo');
    }
}
