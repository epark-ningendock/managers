<?php

namespace App;

use App\Enums\CalendarDisplay;
use App\Helpers\EnumTrait;
use Reshadman\OptimisticLocking\OptimisticLocking;

class Calendar extends SoftDeleteModel
{
    use EnumTrait, OptimisticLocking;

    protected $fillable = [ 'name', 'hospital_id', 'is_calendar_display', 'hospital_id', 'lock_version', 'created_at', 'updated_at' ];

    protected $enums = [ 'is_calendar_display' => CalendarDisplay::class ];

    public function courses()
    {
        return $this->hasMany('App\Course')->orderBy('order');
    }

    public function calendar_days()
    {
        return $this->hasMany('App\CalendarDay');
    }


    public function hospital()
    {
        return $this->belongsTo('App\Hospital');
    }
}
