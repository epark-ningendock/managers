<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedicalTreatmentTime extends Model
{
    protected $fillable = [
        'hospital_id', 'start', 'end', 'mon','tue', 'wed', 'thu', 'fri', 'sat', 'sun', 'hol'
    ];
}
