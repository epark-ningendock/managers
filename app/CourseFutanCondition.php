<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Reshadman\OptimisticLocking\OptimisticLocking;

class CourseFutanCondition extends SoftDeleteModel
{
    use SoftDeletes, OptimisticLocking;

    protected $dates = [
        'deleted_at'];

    protected $fillable = [
        'course_id',
        'kenshin_sys_course_id',
        'jouken_no',
        'sex',
        'honnin_kbn',
        'futan_kingaku',
        'created_at',
        'updated_at'
        ];

    public function courses()
    {
        return $this->belongsTo(KenshinSysCourse::class);
    }

    public function course_target_ages()
    {
        return $this->hasMany('App\CourseTargetAge');
    }
}
