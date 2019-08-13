<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicalTreatmentTime extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_id', 'start', 'end', 'mon','tue', 'wed', 'thu', 'fri', 'sat', 'sun', 'hol', 'status', 'created_at', 'updated_at'
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


    public static function getDefaultFieldValues($attributes = [])
    {
        return array_merge([
//            'hospital_id' => 0,
//            'start' => '-',
//            'end' => '-',
            'mon'=> 0,
            'tue'=> 0,
            'wed'=> 0,
            'thu'=> 0,
            'fri'=> 0,
            'sat'=> 0,
            'sun'=> 0,
//            'hol'=> 0,
//            'status'=> 1,
        ], $attributes);
    }
}
