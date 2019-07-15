<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DistrictCode extends Model
{
    protected $fillable = [
      'district_code', 'prefecture_id', 'name', 'kana', 'status'
    ];
}
