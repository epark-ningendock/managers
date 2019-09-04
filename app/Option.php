<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Reshadman\OptimisticLocking\OptimisticLocking;

class Option extends SoftDeleteModel
{
    use SoftDeletes, OptimisticLocking;

    protected $dates = ['deleted_at'];

    protected $fillable = [
            'hospital_id', 'name', 'confirm', 'price', 'tax_class_id', 'order', 'status', 'lock_version', 'created_at', 'updated_at'
        ];

    public function reservation_options()
    {
        return $this->hasMany('App\ReservationOption');
    }
}
