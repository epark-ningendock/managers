<?php

namespace App;

use Carbon\Carbon;

class TaxClass extends SoftDeleteModel
{
    const TEN_PERCENT = 1.1;
    protected $fillable = [ 'name', 'rate', 'life_time_from', 'life_time_to' ];

    public function nowTax() {
        $now = Carbon::today()->toDateString();
        return TaxClass::where('life_time_from', '>=', $now)
            ->where('life_time_to', '<=', $now)
            ->first()->rate;
    }
}
