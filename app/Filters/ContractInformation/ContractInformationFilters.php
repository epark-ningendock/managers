<?php

namespace App\Filters\ContractInformation;

use App\Filters\QueryFilters;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class ContractInformationFilters extends QueryFilters
{
    public function search_text($search_text)
    {
        return $this->builder->where(function($q) use ($search_text){
            $q->where('property_no', $search_text)
                ->orWhere('contractor_name', $search_text)
                ->orWhere('contractor_name_kana', $search_text)
                ->orWhere('representative_name', $search_text)
                ->orWhere('representative_name_kana', $search_text)
                ->orWhere('tel', 'like', "%$search_text%")
                ->orWhereHas('hospital', function($q) use ($search_text) {
                    $q->where('name', $search_text)->orWhere('kana', $search_text);
                });
        });
    }

    public function status($status)
    {
        if ( $status == 'CANCELLED') {
            return $this->builder->where(function($q) {
                $q->whereNotNull('cancellation_date')
                    ->whereDate('cancellation_date', '<=', Carbon::today());
            });
        } else if ($status ==  'UNDER_CONTRACT') {
            return $this->builder->where(function($q) {
                $q->whereNull('cancellation_date')
                    ->orWhereDate('cancellation_date', '>', Carbon::today());
            });
        }
    }

    public function property_no_sorting($sorting)
    {
        return $this->builder->orderBy('property_no', $sorting);
    }
}
