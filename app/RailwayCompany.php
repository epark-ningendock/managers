<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RailwayCompany extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'es_code',
        'name',
        'status',
        'created_at',
        'updated_at',
    ];
}
