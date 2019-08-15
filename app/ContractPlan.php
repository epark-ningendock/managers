<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContractPlan extends Model
{
    protected $fillable = [
        'plan_code',
        'plan_name',
        'fee_rate',
        'monthly_contract_fee',
        'created_at',
        'updated_at',
    ];
}
