<?php

namespace App;


class Course extends SoftDeleteModel
{
    protected $fillable = [
        'hospital_id', 'code', 'name', 'price', 'order', 'status'
    ];

    protected $attributes = [
      'course_cancel' => '0'
    ];

    public function course_detail() {
        return $this->hasOne('App\CourseDetail');
    }

    public function course_question() {
        return $this->hasOne('App\CourseQuestion');
    }

    public function course_image() {
        return $this->hasOne('App\CourseImage');
    }
}
