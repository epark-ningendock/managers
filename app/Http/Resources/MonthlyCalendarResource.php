<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class MonthlyCalendarResource extends Resource
{
    /**
     * 検査コース（月別） resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // $this => $courses
        // 対象データは６か月まで
        $to = date('Y-m-d 00:00:00', strtotime('+5 month'));
        if (!isset($this->calendar_days)) return;
        $days = $this->calendar_days->filter(function ($d) use ($to) {
            return strtotime($d->date) <= strtotime($to);
        });

        // dateと予約可否のみ抽出
        $d = $days->pluck('appoint_status', 'date');

        // yyyymmでgroup化
        $e = $d->groupBy(function ($item, $key) {
            return date('Ym', strtotime($key));
        });
        return $e->map(function ($c, $key) {
            return [
                'yyyymm' => $key,
                // 予約可否配列の積をとり、0になればどこかに「受付可能(0)」あり
                'apoint_ok' => array_product($c->toArray()) === 0 ? 1 : 0,
            ];
        })->sortBy('yyyymm')->toArray();
    }
}
