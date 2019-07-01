<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HospitalDetail extends Model
{
    protected $fillable = [
        'hospital_id', 'minor_classification_id', 'select_status', 'inputstring', 'status'
    ];
}
