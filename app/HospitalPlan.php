<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HospitalPlan extends Model
{
    protected $fillable = ['hospital_id', 'contract_plan_id', 'from', 'to'];

    protected $dates = ['from', 'to'];

    public function contractPlan()
    {
        return $this->belongsTo(ContractPlan::class)
            ->withDefault();
    }

}
