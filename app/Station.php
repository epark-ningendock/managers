<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Station extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'es_code',
        'prefecture_id',
        'name',
        'kana',
        'longitude',
        'latitude',
        'status',
        'created_at',
        'updated_at',
    ];

    /**
     * @return BelongsToMany
     */
    public function rails(): BelongsToMany
    {
        return $this->belongsToMany(Rail::class);
    }
}
