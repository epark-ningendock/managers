<?php

namespace App;

use App\Enums\WebReception;
use App\Helpers\EnumTrait;

class Course extends SoftDeleteModel
{
    use EnumTrait;

    protected $fillable = [
        'hospital_id',
        'web_reception',
        'is_category',
        'code',
        'name',
        'calendar_id',
        'course_point',
        'course_notice',
        'course_cancel',
        'reception_start_date',
        'reception_end_date',
        'reception_acceptance_date',
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

    protected $enums = [ 'web_reception' => WebReception::class ];

    public function course_options()
    {
        return $this->hasMany('App\CourseOption');
    }

    public function course_details()
    {
        return $this->hasMany('App\CourseDetail');
    }

    public function course_questions()
    {
        return $this->hasMany('App\CourseQuestion')->orderBy('question_number');
    }

    public function course_images()
    {
        return $this->hasMany('App\CourseImage');
    }

    public function hospital()
    {
        return $this->hasOne('App\Hospital');
    }

    public function calendar()
    {
        return $this->hasOne('App\Calendar');
    }
}
