<?php

namespace App;

use App\Enums\CalendarDisplay;
use App\Helpers\EnumTrait;
use Reshadman\OptimisticLocking\OptimisticLocking;

class CalendarBaseWaku extends SoftDeleteModel
{

    protected $fillable = [
        'hospital_id',
        'calendar_id',
        'mon',
        'tue',
        'wed',
        'thu',
        'fri',
        'sat',
        'sun',
        'hol',
        'created_at',
        'updated_at'
    ];

    public function calendars()
    {
        return $this->belongsTo('App\Calendar');
    }
}
