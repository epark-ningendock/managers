<?php

namespace App;


class CourseQuestion extends SoftDeleteModel
{
    protected $fillable = [
        'course_id',
        'question_number',
        'is_question',
        'question_title',
        'answer01',
        'answer02',
        'answer03',
        'answer04',
        'answer05',
        'answer06',
        'answer07',
        'answer08',
        'answer09',
        'answer10'
    ];

    public function course() {
        return $this->belongsTo('App\Course');
    }
}
