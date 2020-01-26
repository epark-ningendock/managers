<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Reshadman\OptimisticLocking\OptimisticLocking;

class KenshinSysCourseWaku extends SoftDeleteModel
{
    use SoftDeletes;

    protected $dates = [
        'deleted_at'];

    protected $fillable = [
        'kenshin_sys_course_id',
        'kenshin_sys_course_no',
        'year_month',
        'waku_kbn',
        'created_at',
        'updated_at'
        ];

    public function courses()
    {
        return $this->belongsTo(KenshinSysCourse::class);
    }
}
