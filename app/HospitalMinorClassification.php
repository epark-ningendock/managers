<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HospitalMinorClassification extends Model
{
    protected $fillable = [
        'middle_classification_id', 'name', 'status', 'order', 'is_icon', 'icon_name'
    ];
}
