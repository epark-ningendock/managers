<?php

namespace App;

use App\Filters\Filterable;
use Illuminate\Database\Eloquent\SoftDeletes;

class HospitalOptionPlan extends SoftDeleteModel
{
    use Filterable, SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $fillable = [
        'hospital_id', 'option_plan_id'
    ];

    public function hospital()
    {
        return $this->belongsTo('App\Hospital');
    }

    public function option_plan()
    {
        return $this->belongsTo('App\OptionPlan');
    }

    public function billing_option_plans() {
        return $this->hasOne('App\BillingOptionPlan', 'option_plan_id', 'option_plan_id');
    }

    /**
     * @param $billing_id
     * @param $option_plan_id
     * @return int
     */
    public function optionPlanAdjustmentPrice($billing_id) {
        $billing_option_plan = BillingOptionPlan::where('billing_id', $billing_id)
            ->where('option_plan_id', $this->option_plan_id)
            ->first();

        if ($billing_option_plan) {
            return $billing_option_plan->adjustment_price;
        }

        return 0;
    }
}
