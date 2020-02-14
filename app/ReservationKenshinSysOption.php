<?php

namespace App;

class ReservationKenshinSysOption extends SoftDeleteModel
{
    protected $fillable = ['reservation_id', 'kenshin_sys_option_id', 'kenshin_sys_option_price', 'status'];

    public function reservation()
    {
        return $this->belongsTo('App\Reservation');
    }

    public function kenshin_sys_options()
    {
        return $this->belongsTo('App\KenshinSysOption');
    }
}
