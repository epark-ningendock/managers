<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HospitalDetail extends Model
{
    protected $fillable = [
        'hospital_id', 'minor_classification_id', 'select_status', 'inputstring', 'status', 'created_at', 'updated_at'
    ];

    public function minor_classification()
    {
        return $this->belongsTo('App\HospitalMinorClassification', 'minor_classification_id')->withTrashed();
    }

    public function hospital()
    {
        return $this->belongsTo('App\Hospital')->withTrashed();
    }
}
