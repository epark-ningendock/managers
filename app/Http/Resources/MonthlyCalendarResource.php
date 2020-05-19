<?php

namespace App\Http\Resources;

use App\Calendar;
use App\CalendarDay;
use App\Enums\CalendarDisplay;
use App\KenshinSysCourseWaku;
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

        if ($this->kenshin_relation_flg && !empty($this->kenshin_sys_courses) && count($this->kenshin_sys_courses) > 0) {
            $results = [];
            if (!empty($this->kenshin_sys_courses[0]->course_futan_conditions)
                && count($this->kenshin_sys_courses[0]->course_futan_conditions) > 0) {
                $from = Carbon::today()->format('Ym');
                $to = Carbon::today()->addMonthsNoOverflow(2)->endOfMonth()->format('Ym');
                if (empty($this->kenshin_sys_courses[0]->course_futan_conditions[0]->jouken_no)) {
                    $course_wakus = KenshinSysCourseWaku::where('kenshin_sys_course_id', $this->kenshin_sys_courses[0]->id)
//                        ->whereNull('jouken_no')
                        ->where('year_month', '>=', $from)
                        ->where('year_month', '<=', $to)
                        ->orderBy('year_month')
                        ->get();
                } else {
                    $course_wakus = KenshinSysCourseWaku::where('kenshin_sys_course_id', $this->kenshin_sys_courses[0]->id)
                        ->where('jouken_no', $this->kenshin_sys_courses[0]->course_futan_conditions[0]->jouken_no)
                        ->where('year_month', '>=', $from)
                        ->where('year_month', '<=', $to)
                        ->orderBy('year_month')
                        ->get();
                }

                if ($course_wakus) {
                    foreach ($course_wakus as $course_waku) {
                        if ($course_waku->waku_kbn == 1) {
                            $appoint_ok = 1;
                        } else {
                            $appoint_ok = 0;
                        }
                        $results[] = ['yyyymm' => $course_waku->year_month, 'apoint_ok' =>  $appoint_ok];
                    }
                }
            }

            for($i = count($results); $i < 3; $i++) {
                $ym = Carbon::today()->addMonthsNoOverflow($i)->format('Ym');
                $results[] = ['yyyymm' => $ym, 'apoint_ok' =>  0];
            }

            return $results;

        } else {
            $from = Carbon::today();
            $to = Carbon::today()->addMonthsNoOverflow(2)->endOfMonth()->toDateString();
            $start_month = $this->reception_start_date / 1000;
            $start_day = $this->reception_start_date % 1000;
            $from = $from->addMonthsNoOverflow($start_month)->addDays($start_day);

            $calendar = $this->calendar;
            if (!$calendar) {
                $disp_flg = CalendarDisplay::HIDE;
            } else {
                $disp_flg = $calendar->is_calendar_display;
            }

            $monthly_wakus = CalendarDay::where('calendar_id', $this->calendar_id)
                ->where('date', '>=', $from)
                ->where('date', '<=', $to)
                ->where('is_holiday', 0)
                ->where('is_reservation_acceptance', 0)
                ->get()
                ->groupBy(function ($row) {
                    return $row->date->format('Ym');
                })
                ->map(function ($day) {
                    return collect([$day->sum('reservation_frames'), $day->sum('reservation_count'), $day[0]->date->format('Ym')]);
                });

            $results = [];

            if ($from->month > Carbon::today()->month) {
                $ym = Carbon::today()->format('Ym');
                $results[] = ['yyyymm' => $ym, 'apoint_ok' =>  0];
            }

            $target_ym1 = Carbon::today()->format('Ym');
            $target_ym2 = Carbon::today()->addMonthsNoOverflow(1)->format('Ym');
            $target_ym3= Carbon::today()->addMonthsNoOverflow(2)->format('Ym');
            $target_month = [$target_ym1, $target_ym2, $target_ym3];

            foreach ($monthly_wakus as $monthly_waku) {
                $appoint_ok = 0;
                if ($disp_flg == strval(CalendarDisplay::SHOW) && ($monthly_waku[0] > $monthly_waku[1])) {
                    $appoint_ok = 1;
                }
                $results[] = ['yyyymm' => $monthly_waku[2], 'apoint_ok' =>  $appoint_ok];
            }

            foreach ($target_month as $target_ym) {
                $exist_flg = false;
                foreach ($results as $r) {
                    if ($target_ym == $r['yyyymm']) {
                        $exist_flg = true;
                    }
                }
                if (!$exist_flg) {
                    $results[] = ['yyyymm' => $target_ym, 'apoint_ok' =>  0];
                }
            }

            foreach ($results as $key => $r) {
                $id[$key] = $r['yyyymm'];
            }


            array_multisort($id, SORT_ASC, $results);

//            for($i = count($results); $i < 3; $i++) {
//                $ym = Carbon::today()->addMonthsNoOverflow($i)->format('Ym');
//                $results[] = ['yyyymm' => $ym, 'apoint_ok' =>  0];
//            }

            return $results;
        }

    }
}
