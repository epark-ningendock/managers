<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class CourseMeta extends SoftDeleteModel
{
    use SoftDeletes;
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $fillable = [
        'course_id',
        'hospital_id',
        'category_exam_name',
        'category_disease_name',
        'category_part_name',
        'category_exam',
        'category_disease',
        'category_part',
        'meal_flg',
        'pear_flg',
        'female_doctor_flg',
        'created_at',
        'updated_at'
    ];

    public function hospitals()
    {
        return $this->belongsTo('App\Hospital');
    }

    public function courses()
    {
        return $this->belongsTo('App\Course');
    }
}
