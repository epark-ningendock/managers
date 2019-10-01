<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Reshadman\OptimisticLocking\OptimisticLocking;

class Lock extends Model
{
    protected $guarded = ['id'];
    use OptimisticLocking;
    //
    public function hospital()
    {
        return $this->belongsTo('App\Hospital');
    }

    public function scopeByHospitalIdAndModel($query, $model_name, $hospital_id)
    {
        $query->where('hospital_id',$hospital_id)->where('model',$model_name);

        return $query;
    }
}
