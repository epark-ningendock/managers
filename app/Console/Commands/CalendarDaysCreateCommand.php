<?php

namespace App\Console\Commands;

use App\Billing;
use App\Calendar;
use App\CalendarBaseWaku;
use App\CalendarDay;
use App\Enums\BillingStatus;
use App\Enums\Status;
use App\Hospital;
use App\HospitalHolidayBase;
use App\MedicalTreatmentTime;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Yasumi\Yasumi;

class CalendarDaysCreateCommand extends Command
{
    protected $signature = 'calendar-day-create 
                            {--addmonth= : 追加月数}';
    protected $description = 'カレンダー基本枠を作成する';

    /**
     * カレンダーごとの基本枠を生成する
     *
     * @return バッチ実行成否
     */
    public function handle()
    {
        $this->info('処理を開始します。');
        // カレンダー作成開始加算月
        $add_month = $this->option('addmonth');
        if (empty($add_month)) {
            $add_month = 6;
        }

        $base_date = Carbon::today()->addMonths($add_month);
        $public_holidays = collect(Yasumi::create('Japan', $base_date->year, 'ja_JP')->getHolidays())->flatten(1);
        // 対象データ取得
        $from_date = Carbon::today()->addMonths($add_month)->startOfMonth();
        $calendars = Calendar::where('auto_update_flg', 1)
            ->where('auto_update_start_date', '<=', $from_date)
            ->where(function ($query) use ($from_date) {
                $query->where('auto_update_end_date', '>=', $from_date)
                    ->orWhereNull('auto_update_end_date');
            })->get();
        foreach ($calendars as $calendar) {
            $this->info('カレンダー処理');
            $date = Carbon::today()->addMonths($add_month)->startOfMonth();
            $end_date = Carbon::today()->addMonths($add_month + 1)->startOfMonth();

            if ($calendar->auto_update_end_date) {
                $auto_update_end_date = Carbon::createFromFormat('Y-m-d', $calendar->auto_update_end_date);
                if ($end_date->year == $auto_update_end_date->year && $end_date->month == $auto_update_end_date->month) {
                    $end_date->day = $auto_update_end_date->day;

                }
            }

            // 指定月の前月に空満設定されていない場合処理しない。
//            $calendar_day_count = CalendarDay::where('calendar_id', $calendar->id)
//                                                ->where('date', '>=', $date->toDateString())
//                                                ->where('date', '<', $end_date->toDateString())
//                                                ->count();
//            if ($calendar_day_count == 0) {
//                continue;
//            }

            $calendar_base_waku = CalendarBaseWaku::where('hospital_id', $calendar->hospital_id)
                                                    ->where('calendar_id', $calendar->id)->first();
            if (!$calendar_base_waku) {
                continue;
            }

            $medical_treatment_times = MedicalTreatmentTime::where('hospital_id', $calendar->hospital_id)->get();

            $mon_flg = 1;
            $tue_flg = 1;
            $wed_flg = 1;
            $thu_flg = 1;
            $fri_flg = 1;
            $sat_flg = 1;
            $sun_flg = 1;
            $hol_flg = 1;
            foreach ($medical_treatment_times as $m) {
                if ($m->mon == 1) {
                    $mon_flg = 0;
                }
                if ($m->tue == 1) {
                    $tue_flg = 0;
                }
                if ($m->wed == 1) {
                    $wed_flg = 0;
                }
                if ($m->thu == 1) {
                    $thu_flg = 0;
                }
                if ($m->fri == 1) {
                    $fri_flg = 0;
                }
                if ($m->sat == 1) {
                    $sat_flg = 0;
                }
                if ($m->sun == 1) {
                    $sun_flg = 0;
                }
                if ($m->hol == 1) {
                    $hol_flg = 0;
                }
            }

            while ($date->lt($end_date)) {
                $calendar_day = CalendarDay::where('calendar_id', $calendar->id)
                    ->where('date', $date->toDateString())
                    ->first();
                if ($calendar_day) {
                    $date = $date->addDay(1);
                    continue;
                }

                $calendar_day = new CalendarDay();
                $calendar_day->date = $date;
                $calendar_day->calendar_id = $calendar->id;
                $p_holiday = $public_holidays->first(function ($h) use ($date) {
                    return $date->isSameDay($h);
                });
                if ($p_holiday) {
                    $calendar_day->is_holiday = $hol_flg;
                    $calendar_day->reservation_frames = $calendar_base_waku->hol;
                } elseif ($date->dayOfWeek == 0) {
                    $calendar_day->is_holiday = $sun_flg;
                    $calendar_day->reservation_frames = $calendar_base_waku->sun;
                } elseif ($date->dayOfWeek == 1) {
                    $calendar_day->is_holiday = $mon_flg;
                    $calendar_day->reservation_frames = $calendar_base_waku->mon;
                } elseif ($date->dayOfWeek == 2) {
                    $calendar_day->is_holiday = $tue_flg;
                    $calendar_day->reservation_frames = $calendar_base_waku->tue;
                } elseif ($date->dayOfWeek == 3) {
                    $calendar_day->is_holiday = $wed_flg;
                    $calendar_day->reservation_frames = $calendar_base_waku->wed;
                } elseif ($date->dayOfWeek == 4) {
                    $calendar_day->is_holiday = $thu_flg;
                    $calendar_day->reservation_frames = $calendar_base_waku->thu;
                } elseif ($date->dayOfWeek == 5) {
                    $calendar_day->is_holiday = $fri_flg;
                    $calendar_day->reservation_frames = $calendar_base_waku->fri;
                } elseif ($date->dayOfWeek == 6) {
                    $calendar_day->is_holiday = $sat_flg;
                    $calendar_day->reservation_frames = $calendar_base_waku->sat;
                }

                $calendar_day->is_reservation_acceptance = 0;
                $calendar_day->reservation_count = 0;
                $calendar_day->status = Status::VALID;
                $calendar_day->save();
                $date = $date->addDay(1);
            }
        }
    }
}
