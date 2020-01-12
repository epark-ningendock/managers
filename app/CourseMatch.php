<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseMatch extends Model
{
    /**
     * モデルと関連しているテーブル
     *
     * @var string
     */
    protected $table = 'course_match';

    public function courses()
    {
        return $this->belongsTo(Course::class);
    }
    public function kenshin_sys_courses()
    {
        return $this->belongsTo(KenshinSysCourse::class);
    }
}
