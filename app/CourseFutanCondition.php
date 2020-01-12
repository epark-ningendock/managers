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
        'kenshin_sys_hospital_id',
        'kenshin_sys_dantai_no',
        'kenshin_sys_course_no',
        'jouken_no',
        'sex',
        'honnin_kbn',
        'futan_kingaku',
        'created_at',
        'updated_at'
        ];

    // tak性別
    public static function tak_gendars()
    {
        return new Choice([
            '1' => ['label' => '男', 'value' => '1'],
            '2' => ['label' => '女', 'value' => '2'],
            '3' => ['label' => 'すべて', 'value' => '3'],
        ]);
    }

    public function courses()
    {
        return $this->belongsTo(KenshinSysCourse::class);
    }

    public function course_target_ages()
    {
        return $this->hasMany(TargetAge::class);
    }
}
