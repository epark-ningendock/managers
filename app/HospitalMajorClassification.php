<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HospitalMajorClassification extends Model
{
    protected $fillable = [
        'name', 'status', 'order', 'is_icon', 'icon_name'
    ];
}
