<?php

namespace App\Http\Resources;

use App\Course;
use App\Enums\Status;
use App\Hospital;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;
use App\HospitalPlan;
use App\ContractPlan
;

class HospitalFeerateResource extends Resource
{
    public function toArray($request)
    {
        return [
            'status' => 0,
            'fee_rate' => $this->fee_rate,
        ];
    }
 
}
