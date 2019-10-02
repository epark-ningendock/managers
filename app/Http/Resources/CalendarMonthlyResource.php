<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CalendarMonthlyResource extends JsonResource
{
    /**
     * 検査コース空満情報（月別） resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // dateと予約可否のみ抽出
        $d = $this->calendar_days->pluck('appoint_status', 'date');

        // yyyymmでgroup化
        $e = $d->groupBy(function ($item, $key) {
            return date('Ym', strtotime($key));
        });
        return [
            'status' => 0,
            'no' => $this->hospital->id,
            'hospital_code' => $this->hospital->contract_information->code,
            'course_no' => $this->id,
            'course_code' => $this->code,
            'month_calender' => $e->map(function ($c, $key) {
                return (object)[
                    'yyyymm' => $key,
                    // 予約可否配列の積をとり、0になればどこかに「受付可能(0)」あり
                    'apoint_ok' => array_product($c->toArray()) === 0 ? 1 : 0,
                ];
            })->toArray(),
        ];
    }
}
