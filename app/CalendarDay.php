<?php

namespace App;

class CalendarDay extends SoftDeleteModel
{
    protected $fillable = [ 'date', 'holiday_flg', 'reservation_flg', 'reservation_flames', 'reservation_id' ];
}
