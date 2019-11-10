<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OldOption extends Model
{

    protected $fillable = [
        'hospital_no',
        'option_group_cd',
        'option_cd',
        'option_id'
    ];

}
