<?php

namespace App;

class Customer extends BaseModel
{
    protected $guarded = [
        'id',
    ];

    public function hospitals()
    {
        return $this->HasMany('App\Hospital');
    }
}
