<?php

namespace App;

class CourseImage extends SoftDeleteModel
{
    protected $fillable = [ 'course_id', 'name', 'path' ];

    public function course()
    {
        return $this->belongsTo('App\Course');
    }
}
