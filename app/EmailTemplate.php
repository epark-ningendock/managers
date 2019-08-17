<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Reshadman\OptimisticLocking\OptimisticLocking;

class EmailTemplate extends Model
{
    use OptimisticLocking;

    protected $fillable = [
        'title',
        'text',
        'hospital_id',
        'lock_version'
    ];
}
