<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;

class CalendarDailyResource extends Resource
{

    /**
     * 検査コース空満情報（日別）resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $reserv_enable_date = Carbon::today()->subMonth(floor($this->reception_start_date / 1000))->subDay($this->reception_start_date % 1000);
        $reserv_enableto_date = Carbon::today()->addMonth(floor($this->reception_end_date / 1000))->addDay($this->reception_end_date % 1000);

        $all_calendars = $this->calendar_days;

        $results = [];

        foreach ($all_calendars as $calendar_day) {
            $holiday_flg = 0;
            if ($calendar_day->is_holiday == 1) {
                $holiday_flg = 1;
            }

            $appoint_status = 0;
            if ($calendar_day->date->lt($reserv_enable_date)) {
                $appoint_status = 1;
            }

            if ($calendar_day->date->gt($reserv_enableto_date)) {
                $appoint_status = 2;
            }

            if ($calendar_day->reservation_frames <= $calendar_day->reservation_count) {
                $appoint_status = 2;
            }

            $results[] = [$calendar_day->date->format('Ymd') => ['appoint_status' =>$appoint_status, 'reservation_frames' => $calendar_day->reservation_frames, 'appoint_num' => $calendar_day->reservation_count, 'closed_day' => $holiday_flg]];

        }

        return $results;

    }
}