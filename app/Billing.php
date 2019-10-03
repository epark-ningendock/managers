<?php

namespace App;

use App\Filters\Filterable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Billing extends SoftDeleteModel
{
    use Filterable, SoftDeletes;
    protected $fillable = ['hospital_id', 'contract_plan_id', 'from', 'to', 'status'];

    protected $dates = ['from', 'to'];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $fillable = [
        'hospital_id', 'billing_month', 'status'
    ];

    public function contractPlan()
    {
        return $this->belongsTo(ContractPlan::class);
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }
}
