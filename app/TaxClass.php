<?php

namespace App;

class TaxClass extends SoftDeleteModel
{
    const TEN_PERCENT = 1.1;
    protected $fillable = [ 'name', 'rate', 'life_time_from', 'life_time_to' ];
}
