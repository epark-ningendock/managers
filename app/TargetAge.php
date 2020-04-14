<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Reshadman\OptimisticLocking\OptimisticLocking;

class TargetAge extends SoftDeleteModel
{
    use SoftDeletes;

    protected $dates = [
        'deleted_at'];

    protected $fillable = [
        'course_futan_condition_id',
        'target_age',
        'created_at',
        'updated_at'
        ];

    public function course_futan_conditions()
    {
        return $this->belongsTo('App\CourseFutanCondition');
    }
}
