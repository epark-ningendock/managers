<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rail extends Model
{
    protected $fillable = [
        'es_code', 'name', 'status'
    ];
}
