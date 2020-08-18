<?php

namespace App;

use App\Enums\CalendarDisplay;
use App\Helpers\EnumTrait;
use Reshadman\OptimisticLocking\OptimisticLocking;

class HospitalHolidayBase extends SoftDeleteModel
{

    protected $fillable = [
        'hospital_id',
        'mon_hol_flg',
        'tue_hol_flg',
        'wed_hol_flg',
        'thu_hol_flg',
        'fri_hol_flg',
        'sat_hol_flg',
        'sun_hol_flg',
        'hol_hol_flg',
        'created_at',
        'updated_at'
    ];

    public function hospitals()
    {
        return $this->belongsTo('App\Hospital');
    }
}
