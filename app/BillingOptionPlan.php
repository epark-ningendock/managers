<?php

namespace App;

use App\Filters\Filterable;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillingOptionPlan extends SoftDeleteModel
{
    use Filterable, SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $fillable = [
        'billing_id', 'option_plan_id', 'adjustment_price'
    ];

    public function option_plans()
    {
        return $this->belongsTo(OptionPlan::class);
    }

    public function billings()
    {
        return $this->belongsTo(Billing::class);
    }
}
