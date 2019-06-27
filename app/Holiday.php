<?php

namespace App;

class Holiday extends SoftDeleteModel
{
    protected $fillable = [ 'date', 'hospital_id' ];

    protected $dates = [ 'date' ];
}
