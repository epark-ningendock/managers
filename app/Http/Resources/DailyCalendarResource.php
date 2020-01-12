<?php

namespace App\Http\Resources;

use App\CalendarDay;
use App\Enums\CalendarDisplay;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;

class DailyCalendarResource extends Resource
{
    /**
     * 検査コース（日別） resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $from_date = Carbon::today()->startOfMonth()->toDateString();
        $to_date = Carbon::today()->addMonthsNoOverflow(5)->endOfMonth()->toDateString();

        $reserv_enable_date = Carbon::today()->subMonth(floor($this->reception_start_date / 1000))->subDay($this->reception_start_date % 1000);
        $reserv_enableto_date = Carbon::today()->addMonth(floor($this->reception_end_date / 1000))->addDay($this->reception_end_date % 1000);
        $calendar = $this->calendar;
        $disp_flg = $calendar->is_calendar_display;
        $calendar_days = CalendarDay::where('calendar_id', $this->calendar_id)
            ->where('date', '>=', $from_date)
            ->where('date', '<=', $to_date)
            ->get();

        $results = [];

        foreach ($calendar_days as $calendar_day) {

            $is_reservation_acceptance = $calendar_day->is_reservation_acceptance;
            if ($disp_flg == strval(CalendarDisplay::HIDE)
                || $calendar_day < $reserv_enable_date
                || $calendar_day > $reserv_enableto_date) {
                $is_reservation_acceptance = CalendarDisplay::HIDE;
            }

            $results[] = ['date' => $calendar_day->date->format('Ymd'),
                'frames' => $calendar_day->reservation_frames - $calendar_day->reservation_count,
                'is_reservation_acceptance' => $is_reservation_acceptance,
                'is_holiday' => $calendar_day->is_holiday];

        }

        return $results;
    }
}
