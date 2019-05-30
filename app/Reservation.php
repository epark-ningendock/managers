<?php

namespace App;

class Reservation extends SoftDeleteModel
{
    protected $guarded = [
        'id',
    ];

    public function hospital()
    {
        return $this->belongsTo('App\Hospital');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }
}
