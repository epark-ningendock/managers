<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rail extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'es_code',
        'railway_company_id',
        'name',
        'status',
        'created_at',
        'updated_at',
    ];

    /**
     * @return BelongsToMany
     */
    public function stations(): BelongsToMany
    {
        return $this->belongsToMany(Station::class);
    }

    /**
     * @return BelongsToMany
     */
    public function prefectures(): BelongsToMany
    {
        return $this->belongsToMany(Prefecture::class);
    }
}
