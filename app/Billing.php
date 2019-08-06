<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class Billing extends SoftDeleteModel
{
    use SoftDeletes;

    protected $guarded = [
        'id',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $fillable = [
        'hospital_id', 'billing_month', 'status'
    ];
}
