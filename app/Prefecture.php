<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Prefecture extends BaseModel
{
    protected $fillable = [
        'name',
        'code',
        'area_no',
        'order',
        'created_at',
        'updated_at'
    ];

    public function prefecture_rail()
    {
        return $this->belongsToMany('App\PrefectureRail', 'prefecture_id');
    }

    public function hospitals()
    {
        return $this->hasMany('App\Hospital');
    }

    public function district_codes()
    {
        return $this->hasMany('App\DistrictCode');
    }
}
