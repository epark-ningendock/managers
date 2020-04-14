<?php

namespace App\Filters\Billing;

use App\Enums\Status;
use App\Filters\QueryFilters;
use App\Hospital;
use Illuminate\Database\Eloquent\Builder;

class BillingFilters extends QueryFilters
{

    public function status($status)
    {
        return $this->builder->where('status', $status);
    }

    public function hospital_name($hospital_name)
    {
        $hospitals = Hospital::where('name', 'LIKE', "%". $hospital_name . "%" )
            ->where('status', '<>', Status::DELETED)
            ->get();

        if ( count($hospitals) > 0 ) {
            return  $this->builder->whereIn('hospital_id', $hospitals->pluck('id')->toArray());
        }
    }
}
