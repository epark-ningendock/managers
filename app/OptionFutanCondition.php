<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Reshadman\OptimisticLocking\OptimisticLocking;

class OptionFutanCondition extends SoftDeleteModel
{
    use SoftDeletes, OptimisticLocking;

    protected $dates = [
        'riyou_bgn_date',
        'riyou_end_date',
        'deleted_at'];

    protected $fillable = [
        'option_id',
        'kenshin_sys_option_no',
        'jouken_no',
        'sex',
        'honnin_kbn',
        'futan_kingaku',
        'yusen_kbn',
        'riyou_bgn_date',
        'riyou_end_date',
        'created_at',
        'updated_at'
        ];

    public function options()
    {
        return $this->belongsTo('App\Option');
    }

    public function option_target_ages()
    {
        return $this->hasMany('App\OptionTargetAge');
    }
}