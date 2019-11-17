<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Availabil extends Model
{

    protected $fillable = [
        'hospital_no',
        'course_no',
        'reservation_dt',
        'line_id',
        'appoint_number',
        'reservation_frames'
    ];

}
