<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReservationAnswer extends Model
{
    protected $fillable = [
        'reservation_id',
        'course_id',
        'course_question_id',
        'question_title',
        'question_answer01',
        'question_answer02',
        'question_answer03',
        'question_answer04',
        'question_answer05',
        'question_answer06',
        'question_answer07',
        'question_answer08',
        'question_answer09',
        'question_answer10',
        'answer01',
        'answer02',
        'answer03',
        'answer04',
        'answer05',
        'answer06',
        'answer07',
        'answer08',
        'answer09',
        'answer10',
    ];
}
