<?php

namespace App;

class HospitalImage extends SoftDeleteModel
{
    protected $guarded = ['id'];

    public function hospital_category()
    {
        return $this->hasOne('App\HospitalCategory');
    }
}
