<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class Option extends SoftDeleteModel
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
            'hospital_id', 'name', 'confirm', 'price', 'tax_class_id', 'order', 'status'
        ];

    public function reservation_options()
    {
        return $this->hasMany('App\ReservationOption');
    }
}
