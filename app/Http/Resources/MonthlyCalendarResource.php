<?php

namespace App\Http\Resources;

use App\CalendarDay;
use Carbon\Carbon;
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

        $from = Carbon::today();
        $to = Carbon::today()->addMonthsNoOverflow(2)->endOfMonth()->toDateString();
        $start_month = $this->reception_start_date / 1000;
        $start_day = $this->reception_start_date % 1000;
        $from = $from->addMonthsNoOverflow($start_month)->addDays($start_day);

        $monthly_wakus = CalendarDay::where('calendar_id', $this->calendar_id)
            ->where('date', '>=', $from)
            ->where('date', '<=', $to)
            ->where('is_holiday', 0)
            ->where('is_reservation_acceptance', 1)
            ->get()
            ->groupBy(function ($row) {
                return $row->date->format('m');
            })
            ->map(function ($day) {
                return collect([$day->sum('reservation_frames'), $day->sum('reservation_count'), $day[0]->date->format('Ym')]);
            });

        $today = Carbon::today();
        $results = [];
        if ($from->year > $today->year) {
            $month_num = (12 + $from->month) - $today->month;
        } else {
            $month_num = $from->month - $today->month;
        }

        for ($i = 0; $i < $month_num; $i++) {
            $ym = $today->format('Ym');
            $results[] = [$ym, 0];
            $today->addMonthsNoOverflow(1);
        }

        foreach ($monthly_wakus as $monthly_waku) {
            $appoint_ok = 0;
            if ($monthly_waku[0] > $monthly_waku[1]) {
                $appoint_ok = 1;
            }
            $results[] = ['yyyymm' => $monthly_waku[2], 'apoint_ok' =>  $appoint_ok];

        }

        return $results;
    }
}
