<?php

namespace App;

class Customer extends SoftDeleteModel
{
    protected $guarded = [
        'id',
    ];

    public function hospitals()
    {
        return $this->HasMany('App\Hospital');
    }
}
