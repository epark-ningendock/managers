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

class CalendarBaseWakuCreateCommand extends Command
{
    protected $signature = 'calendar-base-waku-create';
    protected $description = 'カレンダー基本枠を作成する';

    /**
     * カレンダーごとの基本枠を生成する
     *
     * @return バッチ実行成否
     */
    public function handle()
    {

            // 対象データ取得
            $calendars = Calendar::all();

            foreach ($calendars as $calendar) {
                $calendar_base_waku = CalendarBaseWaku::where('calendar_id', $calendar->id)->first();
                if ($calendar_base_waku) {
                    continue;
                }
                $calendar_days = CalendarDay::where('calendar_id', $calendar->id)
                    ->where('date', '>=', '2020-06-01')
                    ->where('date', '<=', '2020-07-31')
                    ->get();

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

                $mon = 0;
                $tue = 0;
                $wed = 0;
                $thu = 0;
                $fri = 0;
                $sat = 0;
                $sun = 0;
                $hol = 0;

                foreach ($calendar_days as $c) {

                    $date = $c->date;
                    $date_stre = $date->format('Y-m-d');
                    $date_day_of_week = $date->format('w');
                    if ($date_stre == '2020-07-23'
                        || $date_stre == '2020-07-24'
                        || $date_stre == '2020-05-03'
                        || $date_stre == '2020-05-05') {

                        if ($c->is_holiday == 1) {
                            $hol_flg = true;
                            continue;
                        }
                        if ($hol < $c->reservation_frames) {
                            $hol = $c->reservation_frames;
                        }
                        continue;
                    }
                    if ($date_day_of_week == 0) {
                        if ($sun < $c->reservation_frames) {
                            $sun = $c->reservation_frames;
                        }
                        continue;
                    }
                    if ($date_day_of_week == 1) {
                        if ($mon < $c->reservation_frames) {
                            $mon = $c->reservation_frames;
                        }
                        continue;
                    }
                    if ($date_day_of_week == 2) {
                        if ($tue < $c->reservation_frames) {
                            $tue = $c->reservation_frames;
                        }
                        continue;
                    }
                    if ($date_day_of_week == 3) {
                        if ($wed < $c->reservation_frames) {
                            $wed = $c->reservation_frames;
                        }
                        continue;
                    }
                    if ($date_day_of_week == 4) {
                        if ($thu < $c->reservation_frames) {
                            $thu = $c->reservation_frames;
                        }
                        continue;
                    }
                    if ($date_day_of_week == 5) {
                        if ($fri < $c->reservation_frames) {
                            $fri = $c->reservation_frames;
                        }
                        continue;
                    }
                    if ($date_day_of_week == 6) {
                        if ($sat < $c->reservation_frames) {
                            $sat = $c->reservation_frames;
                        }
                        continue;
                    }
                }

                $calendar_base_waku = new CalendarBaseWaku();
                $calendar_base_waku->hospital_id = $calendar->hospital_id;
                $calendar_base_waku->calendar_id = $calendar->id;
                $calendar_base_waku->mon = $mon;
                $calendar_base_waku->tue = $tue;
                $calendar_base_waku->wed = $wed;
                $calendar_base_waku->thu = $thu;
                $calendar_base_waku->fri = $fri;
                $calendar_base_waku->sat = $sat;
                $calendar_base_waku->sun = $sun;
                $calendar_base_waku->hol = $hol;
                $calendar_base_waku->status = Status::VALID;
                $calendar_base_waku->created_at = Carbon::today();
                $calendar_base_waku->updated_at = Carbon::today();
                $calendar_base_waku->save();


                $hospital_holiday_base = HospitalHolidayBase::where('hospital_id', $calendar->hospital_id)->first();
                if (!$hospital_holiday_base) {
                    $hospital_holiday_base = new HospitalHolidayBase();
                    $hospital_holiday_base->hospital_id = $calendar->hospital_id;
                    $hospital_holiday_base->mon_hol_flg = $mon_flg;
                    $hospital_holiday_base->tue_hol_flg = $tue_flg;
                    $hospital_holiday_base->wed_hol_flg = $wed_flg;
                    $hospital_holiday_base->thu_hol_flg = $thu_flg;
                    $hospital_holiday_base->fri_hol_flg = $fri_flg;
                    $hospital_holiday_base->sat_hol_flg = $sat_flg;
                    $hospital_holiday_base->sun_hol_flg = $sun_flg;
                    $hospital_holiday_base->hol_hol_flg = $hol_flg;
                    $hospital_holiday_base->status = Status::VALID;
                    $hospital_holiday_base->created_at = Carbon::today();
                    $hospital_holiday_base->updated_at = Carbon::today();
                    $hospital_holiday_base->save();
                }

            }
    }
}
