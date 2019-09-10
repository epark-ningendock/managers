<?php

namespace App\Filters\Billing;

use App\Filters\QueryFilters;
use App\Hospital;
use Illuminate\Database\Eloquent\Builder;

class BillingFilters extends QueryFilters
{

	public function billing_month( $billing_month = '2019 09' ) {
		dd('here');
	}

    public function status($status)
    {
        return $this->builder->where('status', $status);
    }

    public function hospital_name($hospital_name)
    {
        $hospitals = Hospital::where('name', 'LIKE', "%". $hospital_name . "%" )->get();

        if ( count($hospitals) > 0 ) {
            return  $this->builder->whereIn('id', $hospitals->pluck('id')->toArray());
        }
    }
}
