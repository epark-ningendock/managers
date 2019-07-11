<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $guarded = [];

    public function staff()
    {
        return $this->hasOne('App\Staff');
    }
}
