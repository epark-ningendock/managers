<?php

namespace App;


class HospitalImage extends SoftDeleteModel
{
    protected $fillable = [
        'hospital_id',
        'name',
        'extension',
        'path',
        'is_display'
    ];
}
