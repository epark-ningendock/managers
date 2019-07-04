<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HospitalMinorClassification extends Model
{
    protected $guarded = [];

    public function middle_classification()
    {
        return $this->belongsTo('App\HospitalMiddleClassification', 'middle_classification_id')->withTrashed();
    }

    public function hospital_details()
    {
        return $this->hasMany('App\HospitalDetail')->orderBy('order');
    }
}
