<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConvertedIdString extends Model
{
    protected $fillable = [
        'table_name',
        'old_id',
        'new_id',
    ];
}
