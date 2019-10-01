<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Prefecture extends BaseModel
{
    protected $fillable = [
        'name',
        'code',
        'created_at',
        'updated_at'
    ];

    public function rails(): BelongsToMany
    {
        return $this->belongsToMany(Rail::class);
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
