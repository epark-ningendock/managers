<?php

namespace App\Http\Resources;

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
        $all_calendars = $this->calendar_days;
        $calendars = [];

        foreach ($all_calendars as $calendar) {
            $frames = $calendar->reservation_frames = $calendar->reservation_count;

            $cal = ['date' => $calendar->date,
                'frames' => $frames,
                'is_reservation_acceptance' => $calendar->is_reservation_acceptance,
                'is_holiday' => $calendar->is_holiday];
            $calendars[] = $cal;
        }

        return $calendars;
    }
}