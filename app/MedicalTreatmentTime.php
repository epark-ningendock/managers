<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedicalTreatmentTime extends Model
{
    protected $fillable = [
        'hospital_id', 'start', 'end', 'mon','tue', 'wed', 'thu', 'fri', 'sat', 'sun', 'hol'
    ];


    public function setStartAttribute($value)
    {
        $this->attributes['start'] = is_null($value) ? '-' : $value;
    }

    public function setEndAttribute($value)
    {
        $this->attributes['end'] = is_null($value) ? '-' : $value;
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = is_null($value) ? '1' : $value;
    }

}
