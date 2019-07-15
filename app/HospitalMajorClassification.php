<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HospitalMajorClassification extends Model
{
    protected $guarded = [];

    public function middle_classifications()
    {
        return $this->hasMany('App\HospitalMiddleClassification')->orderBy('order');
    }
}
