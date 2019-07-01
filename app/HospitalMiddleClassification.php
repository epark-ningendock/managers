<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HospitalMiddleClassification extends Model
{
    protected $fillable = [
        'major_classification_id', 'name', 'status', 'order', 'is_icon', 'icon_name'
    ];
}
