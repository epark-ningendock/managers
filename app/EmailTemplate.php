<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $fillable = [
        'title', 
        'text'
    ];

    public function hospital()
    {
        return $this->belongsTo('App\Hospital');
    }
}
