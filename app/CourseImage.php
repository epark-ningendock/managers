<?php

namespace App;


class CourseImage extends SoftDeleteModel
{
    protected $fillable = [ 'course_id', 'image_order_id', 'hospital_image_id' ];

    public function course() {
        return $this->belongsTo('App\Course');
    }
}
