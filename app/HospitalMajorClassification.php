<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HospitalMajorClassification extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'status',
        'order',
        'is_icon',
        'icon_name',
        'created_at',
        'updated_at',
    ];

    public function middle_classifications()
    {
        return $this->hasMany('App\HospitalMiddleClassification')->orderBy('order');
    }
}
