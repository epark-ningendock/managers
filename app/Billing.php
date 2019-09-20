<?php

namespace App;

use App\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    use Filterable;
    protected $fillable = ['hospital_id', 'billing_month', 'status'];

//    protected $dates = ['billing_month'];


    public function contractPlan()
    {
        return $this->belongsTo(ContractPlan::class);
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

}
