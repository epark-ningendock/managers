<?php

namespace App;

class ReservationOption extends SoftDeleteModel
{
    protected $fillable = ['reservation_id', 'option_id', 'option_price', 'status'];

    public function reservation()
    {
        return $this->belongsTo('App\Reservation');
    }

    public function option()
    {
        return $this->belongsTo('App\Option')->withTrashed();
    }
}
