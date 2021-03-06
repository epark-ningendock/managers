<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HospitalMiddleClassification extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    
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
