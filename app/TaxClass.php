<?php

namespace App;

class TaxClass extends SoftDeleteModel
{
    protected $fillable = [ 'name', 'rate', 'life_time_from', 'life_time_to' ];
}
