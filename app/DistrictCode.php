<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DistrictCode extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'district_code',
        'prefecture_id',
        'name',
        'kana',
        'status',
        'created_at',
        'updated_at',
    ];
}
