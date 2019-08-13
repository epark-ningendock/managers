<?php

namespace App;


class ContractPlan extends SoftDeleteModel
{
    protected $fillable = [ 'plan_code', 'plan_name', 'fee_rate', 'monthly_contract_fee' ];
}
