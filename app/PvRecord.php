<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class PvRecord extends SoftDeleteModel
{
    use SoftDeletes;

    protected $guarded = [
        'id',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'dt'
    ];

    protected $fillable = [
        'hospital_id', 'date_code', 'pv'
    ];

    /**
     * 指定日付以降の医療機関ごとに集計したpv数を返す
     *
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @return Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeGetPvData(Builder $query, Carbon $date)
    {
        $query->select(DB::raw(
            // 医療機関ID
            '`hospital_id`',
            // pv集計
            '`SUM(pv)` AS `pv`'
        ));
        $query->where('created_at', '>', $date);
        $query->groupBy('hospital_id');

        return $query;
    }
}
