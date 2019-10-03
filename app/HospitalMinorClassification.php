<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HospitalMinorClassification extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function middle_classification()
    {
        return $this->belongsTo('App\HospitalMiddleClassification', 'middle_classification_id')->withTrashed();
    }

    public function major_classification()
    {
        return $this->belongsTo('App\HospitalMajorClassification', 'major_classification_id')->withTrashed();
    }

    public function hospital_details()
    {
        return $this->hasMany('App\HospitalDetail')->orderBy('order');
    }
}
