<?php

namespace App;

class CourseOption extends SoftDeleteModel
{
    protected $fillable = [ 'course_id', 'option_id'];

    public function course()
    {
        return $this->belongsTo('App\Course');
    }

    public function option()
    {
        return $this->belongsTo('App\Option')->withTrashed();
    }
}
