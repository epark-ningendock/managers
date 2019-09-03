<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    protected $fillable = ['hospital_id', 'contract_plan_id', 'from', 'to'];

    protected $dates = ['from', 'to'];


    public function contractPlan()
    {
        return $this->belongsTo(ContractPlan::class);
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

}
