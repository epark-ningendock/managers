<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HospitalMiddleClassification extends Model
{
    protected $fillable = [
        'major_classification_id', 'name', 'status', 'order', 'is_icon', 'icon_name'
    ];

    public function major_classification()
    {
        return $this->belongsTo('App\HospitalMajorClassification', 'major_classification_id')->withTrashed();
    }
    
    public function minor_classifications()
    {
        return $this->hasMany('App\HospitalMinorClassification', 'middle_classification_id')->orderBy('order');
    }

    public function minors_with_fregist_order()
    {
        return $this->hasMany('App\HospitalMinorClassification', 'middle_classification_id')->orderBy('is_fregist', 'DESC');
    }
}
