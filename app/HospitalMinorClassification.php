<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HospitalMinorClassification extends Model
{
    protected $fillable = [
        'middle_classification_id', 'name', 'status', 'order', 'is_icon', 'icon_name'
    ];

    public function middle_classification()
    {
        return $this->belongsTo('App\HospitalMiddleClassification', 'middle_classification_id')->withTrashed();
    }

    public function hospital_details()
    {
        return $this->hasMany('App\HospitalDetail')->orderBy('order');
    }
}
