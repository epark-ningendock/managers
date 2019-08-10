<?php

namespace App;

use App\Enums\WebReception;
use App\Helpers\EnumTrait;

class Course extends SoftDeleteModel
{
    use EnumTrait;

    protected $fillable = [
        'hospital_id',
        'calendar_id',
        'code',
        'name',
        'web_reception',
        'is_category',
        'course_sales_copy',
        'course_point',
        'course_notice',
        'course_cancel',
        'is_price',
        'price',
        'is_price_memo',
        'price_memo',
        'regular_price',
        'discounted_p[rice',
        'tax_class',
        'display_setting',
        'pv',
        'pvad',
        'order',
        'cancellation_deadline',
        'reception_start_date',
        'reception_end_date',
        'pre_account_price',
        'is_local_payment',
        'is_pre_account',
        'auto_calc_application',
        'reception_acceptance_date',
        'status',
        'created_at',
        'updated_at',
    ];

    protected $attributes = [
        'course_cancel' => '0'
    ];

    protected $enums = ['web_reception' => WebReception::class];

    public function course_options()
    {
        return $this->hasMany('App\CourseOption');
    }

    public function options()
    {
        return $this->hasManyThrough('App\Option', 'App\CourseOption', 'course_id', 'id', null, 'option_id');
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
