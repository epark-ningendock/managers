<?php

namespace App;

class Holiday extends SoftDeleteModel
{
    protected $fillable = [ 'date', 'hospital_id' ];

    protected $dates = [ 'date' ];


    public function hospital()
    {
    	return $this->belongsTo('App\Hospital');
    }
}
