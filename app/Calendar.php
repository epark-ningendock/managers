<?php

namespace App;

class Calendar extends SoftDeleteModel
{
    protected $fillable = [ 'name', 'hospital_id', 'is_calendar_display', 'hospital_id' ];
}
