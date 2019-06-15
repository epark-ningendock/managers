<?php

namespace App;

class Customer extends BaseModel
{
    const MALE = 'M';
    const FEMALE = 'F';

    protected $dates = [
        'completed_date',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public static $sex = [
        self::MALE => '男性',
        self::FEMALE => '女性',
    ];

    protected $guarded = [
        'id',
    ];

    public function hospitals()
    {
        return $this->HasMany('App\Hospital');
    }
    public function prefecture()
    {
        return $this->belongsTo('App\Prefecture');
    }
}
