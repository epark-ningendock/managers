<?php

namespace App;


class FeeRate extends SoftDeleteModel
{
    
    protected $fillable = [ 'id', 'hospital_id', 'type', 'rate', 'from_date', 'to_date' ];
}
