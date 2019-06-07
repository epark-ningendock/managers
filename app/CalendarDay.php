<?php

namespace App;

class CalendarDay extends SoftDeleteModel
{
    protected $fillable = [ 'date', 'is_holiday', 'is_reservation_acceptance', 'reservation_frames', 'calendar_id' ];

    protected $dates = [ 'date' ];

    public function calendar()
    {
        return $this->hasOne('App\Calendar');
    }
}
