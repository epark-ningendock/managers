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
}
