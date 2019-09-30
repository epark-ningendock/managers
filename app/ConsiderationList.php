<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConsiderationList extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'epark_member_id',
        'hospital_id',
        'course_id',
        'display_kbn',
        'status',
    ];
}
