<?php

namespace App;


use App\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Reshadman\OptimisticLocking\OptimisticLocking;

class ContractInformation extends Model
{
    use OptimisticLocking, Filterable;

    protected $fillable = [
                            'contractor_name_kana',
                            'contractor_name',
                            'application_date',
                            'billing_start_date',
                            'cancellation_date',
                            'representative_name_kana',
                            'representative_name',
                            'postcode',
                            'address',
                            'tel',
                            'fax',
                            'code',
                            'email',
                            'contract_plan_id',
                            'property_no',
                            'hospital_id',
                            'service_start_date',
                            'service_end_date',
                            'lock_version',
                            'hospital_name',
                            'hospital_name_kana',
                            'contract_plan_id'
                        ];

    protected $dates = ['application_date', 'cancellation_date', 'service_start_date', 'service_end_date', 'billing_start_date'];

    public function hospital()
    {
        return $this->belongsTo('App\Hospital');
    }

    public function contract_plan()
    {
        return $this->belongsTo('App\ContractPlan');
    }
}
