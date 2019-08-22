<?php

namespace App;


class FeeRate extends SoftDeleteModel
{
    protected $fillable = [ 'hospital_id', 'type', 'rate', 'from_date', 'to_date' ];
}
