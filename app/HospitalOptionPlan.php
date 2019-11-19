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
}
