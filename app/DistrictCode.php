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

    public function prefecture()
    {
        return $this->belongsTo('App\Prefecture');
    }

    public function hospitals()
    {
        return $this->hasMany('App\Hospital');
    }
}
