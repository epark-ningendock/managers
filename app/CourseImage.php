<?php

namespace App;

use App\Enums\CourseImageType;

class CourseImage extends SoftDeleteModel
{
    protected $fillable = [ 'course_id', 'name', 'extension', 'path', 'type' ];

    protected $enums = ['type' => CourseImageType::class];

    public function course()
    {
        return $this->belongsTo('App\Course');
    }
}
