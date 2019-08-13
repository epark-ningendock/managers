<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicalTreatmentTime extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_id', 'start', 'end', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun', 'hol', 'created_at', 'updated_at'
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
