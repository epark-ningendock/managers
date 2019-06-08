<?php

namespace App;

use App\Enums\CalendarDisplay;
use App\Helpers\EnumTrait;

class Calendar extends SoftDeleteModel
{
    use EnumTrait;

    protected $fillable = [ 'name', 'hospital_id', 'is_calendar_display', 'hospital_id' ];

    protected $enums = [ 'is_calendar_display' => CalendarDisplay::class ];

    public function courses()
    {
        return $this->hasMany('App\Course')->orderBy('order');
    }
}
