<?php

namespace App;

class CalendarDay extends SoftDeleteModel
{
    protected $fillable = [ 'date', 'is_holiday', 'is_reservation_acceptance', 'reservation_frames', 'calendar_id', 'created_at', 'updated_at' ];

    protected $dates = [ 'date' ];

    public function calendar()
    {
        return $this->belongsTo('App\Calendar');
    }
}
