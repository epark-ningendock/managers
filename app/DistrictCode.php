<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DistrictCode extends Model
{
    protected $fillable = [
      'major_classification_id', 'name', 'is_icon', 'icon_name'
    ];
}
