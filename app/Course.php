<?php

namespace App;


class Course extends SoftDeleteModel
{
    protected $fillable = [
        'hospital_id',
        'code',
        'name',
        'course_point',
        'course_notice',
        'course_cancel',
        'reception_start_date',
        'reception_end_date',
        'cancellation_deadline',
        'is_price',
        'price',
        'is_price_memo',
        'price_memo',
        'order',
        'status'
    ];

    protected $attributes = [
      'course_cancel' => '0'
    ];

    public function course_detail() {
        return $this->hasOne('App\CourseDetail');
    }

    public function course_questions() {
        return $this->hasMany('App\CourseQuestion');
    }

    public function course_images() {
        return $this->hasMany('App\CourseImage');
    }
}
