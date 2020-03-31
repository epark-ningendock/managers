<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Carbon\Carbon;

class ReserveVolResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $hospital_code = '';
        if (isset($this->contract_information) && isset($this->contract_information->code)) {
            $hospital_code = $this->contract_information->code;
        }
        return
            collect([])
                ->put('status',0)
                ->put($hospital_code, $this->createhospital())
                ->toArray();
    }

    private function createhospital()
    {
        $results = [];
        $data = $this->courses;
        $today = Carbon::today()->toDateString();
        $from = Carbon::today()->modify("-1 months")->toDateString();

        foreach ($data as $detail) {
            $results[] = [
                'course_no' => $detail->id,
                'r_vol' => $detail->reservations
                        ->where('reservation_date', '>=', $from)
                        ->where('reservation_date', '<=', $today)
                        ->count()
            ];
        }

        return $results;
    }
}
