<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    protected $fillable = [
        'es_code', 'prefecture_id', 'name', 'kana', 'longitude', 'latitude', 'status'
    ];
}
