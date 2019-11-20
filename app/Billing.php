<?php

namespace App;

use App\Filters\Filterable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Billing extends SoftDeleteModel
{
    use Filterable, SoftDeletes;

	protected $enums = [];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

	protected $casts = [
		'id' => 'integer',
        'adjustment_price' => 'integer',
		'status' => 'integer'
	];

    protected $fillable = [
        'hospital_id', 'billing_month', 'adjustment_price', 'status'
    ];

    protected $appends = ['startedDate', 'endedDate'];

    public function contractPlan()
    {
        return $this->belongsTo(ContractPlan::class);
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function billing_option_plans()
    {
        return $this->hasMany(BillingOptionPlan::class);
    }

    public function startedDate()
    {
        return billingDateFilter($this->billing_month)['startedDate'];
    }

    public function endedDate()
    {
        return billingDateFilter($this->billing_month)['endedDate'];
    }


    public function getStartedDateAttribute()
    {
        return $this->startedDate();
    }

    public function getEndedDateAttribute()
    {
        return $this->endedDate();
    }


}
