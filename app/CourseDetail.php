<?php

namespace App;

class CourseDetail extends SoftDeleteModel
{
    protected $fillable = [
        'course_id', 'major_classification_id', 'middle_classification_id', 'minor_classification_id', 'select_status', 'inputstring', 'created_at', 'updated_at'
    ];

    public function course()
    {
        return $this->belongsTo('App\Course');
    }

    public function major_classification()
    {
        return $this->belongsTo('App\MajorClassification')->withTrashed();
    }

    public function middle_classification()
    {
        return $this->belongsTo('App\MiddleClassification')->withTrashed();
    }

    public function minor_classification()
    {
        return $this->belongsTo('App\MinorClassification')->withTrashed();
    }
}
