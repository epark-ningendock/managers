<?php

namespace App\Http\Controllers\Api;

use App\CalendarDay;
use App\Enums\Status;
use App\Http\Requests\CourseRequest;
use App\Http\Requests\CalendarMonthlyRequest;
use App\Http\Requests\CalendarDayRequest;
use App\Http\Controllers\Controller;

use App\Course;
use App\Reservation;
use App\Holiday;

use App\Http\Resources\CourseIndexResource;
use App\Http\Resources\CourseBasicResource;
use App\Http\Resources\CourseContentsResource;
use App\Http\Resources\CalendarMonthlyResource;
use App\Http\Resources\CalendarDailyResource;
use Carbon\Carbon;

class CourseController extends Controller
{
    /**
     * 検査コース情報取得API
     *
     * @param  App\Http\Requests\CourseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function index(CourseRequest $request)
    {
        $course_no = $request->input('course_no');
        $hospital_code = $request->input('hospital_code');

        //検査コースコンテンツ情報取得
        $contents = $this->getCourseContents($hospital_code, $course_no);

        return new CourseIndexResource($contents);
    }

    /**
     * 検査コース基本情報取得API
     *
     * @param  App\Http\Requests\CourseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function basic(CourseRequest $request)
    {
        $course_no = $request->input('course_no');
        $hospital_code = $request->input('hospital_code');
        $course_code = $request->input('course_code');

        $basics = $this->basicCourse($hospital_code, $course_no, $course_code);

        return new CourseBasicResource($basics);
    }

    /**
     * 検査コースコンテンツ情報取得API
     *
     * @param  App\Http\Requests\CourseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function contents(CourseRequest $request)
    {
        $hospital_code = $request->input('hospital_code');
        $course_no = $request->input('course_no');

        $contents = $this->getCourseContents($hospital_code, $course_no);

        return new CourseContentsResource($contents);
    }

    /**
     * 検査コース基本情報取得
     *
     * @param  $hospital_code
     * @param  $course_no
     * @return array
     */
    private function basicCourse($hospital_code, $course_no, $course_code)
    {
        return Course::with([
            'hospital',
            'hospital.contract_information',
            'hospital.district_code',
            'hospital.hospital_details',
            'hospital.hospital_details.minor_classification',
            'hospital.hospital_categories',
            'hospital.hospital_categories.image_order',
            'hospital.hospital_categories.hospital_image'
        ])
//            ->whereHas('hospital.contract_information', function ($query) use ($hospital_code) {
//                $query->where('code', $hospital_code);
//            })
//            ->where('code', $course_code)
            ->where('id', $course_no)
            ->get();
    }

    /**
     * 検査コースコンテンツ情報取得
     *
     * @param  $hospital_code
     * @param  $course_no
     * @return array
     */
    private function getCourseContents($hospital_code, $course_no)
    {
        $data = Course::with([
            'course_details',
            'course_details.major_classification',
            'course_details.major_classification.middle_classifications',
            'course_details.major_classification.middle_classifications.minor_classifications',
            'course_options',
            'course_options.option',
            'course_questions',
            'calendar_days',
            'hospital',
            'hospital.contract_information',
            'hospital.hospital_categories.image_order',
            'hospital.hospital_categories.hospital_image'
        ])
            ->whereHas('hospital.contract_information', function ($query) use ($hospital_code) {
                $query->where('code', $hospital_code);
            })
            ->find($course_no);

        return $data;
    }

    /**
     * 検査コース空満情報（月別）取得API
     *
     * @param  App\Http\Requests\CalendarMonthlyRequest
     * @return
     */
    public function calendar_monthly(CalendarMonthlyRequest $request)
    {
        // 検索条件取得
        $serach_condition = $request->toObject();

        $course = Course::find($serach_condition->course_no);

        // 医療機関の検査コースのカレンダー取得
        $month_data = $this->getMonthReservationEnableInfo($serach_condition, $course);

        $data = ['search_cond' => $serach_condition,  'course' => $course, 'month_data' => $month_data];

        // response
        return new CalendarMonthlyResource($data);
    }

    /**
     * 検査コース空満情報（日別）取得API
     *
     * @param  App\Http\Requests\CalendarDayRequest
     * @return \Illuminate\Http\Response
     */
    public function calendar_daily(CalendarDayRequest $request)
    {
        // 検索条件取得
        $serach_condition = $request->toObject();

        $course = Course::find($serach_condition->course_no);

        // 医療機関の検査コースのカレンダー取得
        $calendar_dailys = $this->getDayReservationEnableInfo($serach_condition, $course);

        $data = ['search_cond' => $serach_condition,  'course' => $course, 'day_data' => $calendar_dailys];

        // response
        return new CalendarDailyResource($data);
    }

    /**
     * 月次予約可否情報を返す
     * @param $serach_condition
     */
    private function getMonthReservationEnableInfo($serach_condition, $course) {

        $from = new Carbon($serach_condition->get_yyyymmdd_from->firstOfMonth());
        $to = new Carbon($serach_condition->get_yyyymmdd_from->endOfMonth());
        $cnt = $serach_condition->get_yyyymmdd_to->diffInMonths($serach_condition->get_yyyymmdd_from);

        $results = [];
        for ($i = 0; $i <= $cnt; $i++ ) {

            $reserv_cnt = Reservation::with([
                'courses' => function ($query) use ($course) {
                    $query->where('calendar_id', $course->calendar_id);
                },
            ])
                ->whereIn('reservation_status', [1, 2, 3])
                ->whereBetween('completed_date', [$from, $to])
                ->count();

            $frames = CalendarDay::where('calendar_id', $course->calendar_id)
                ->where('is_holiday', 0)
                ->where('is_reservation_acceptance', 1)
                ->whereBetween('date', [$from, $to])
                ->sum('reservation_frames');

            $reserv_flg = 0;
            if ($frames > $reserv_cnt) {
                $reserv_flg = 1;
            }

            $results[] = [$from->format('Ym'), $reserv_flg];

            $from->addMonthsNoOverflow(1);
            $to->addMonthsNoOverflow(1);
        }

        return $results;
    }

    /**
     * 日次予約可否情報を返す
     * @param $serach_condition
     */
    private function getDayReservationEnableInfo($serach_condition, $course) {

        $from = new Carbon($serach_condition->get_yyyymmdd_from);
        $to = new Carbon($serach_condition->get_yyyymmdd_from);
        $to = $to->endOfDay();
        $cnt = $serach_condition->get_yyyymmdd_to->diffInDays($serach_condition->get_yyyymmdd_from);

        $reserv_enable_date = Carbon::today()->subMonth(floor($course->reception_end_date / 1000))->subDay($course->reception_end_date % 1000);
        $reserv_enableto_date = Carbon::today()->addMonth(floor($course->reception_start_date / 1000))->addDay($course->reception_start_date % 1000);

        $results = [];
        for ($i = 0; $i <= $cnt; $i++ ) {

            $reserv_cnt = Reservation::with([
                'courses' => function ($query) use ($course) {
                    $query->where('calendar_id', $course->calendar_id);
                },
            ])
                ->whereIn('reservation_status', [1, 2, 3])
                ->whereBetween('completed_date', [$from, $to])
                ->count();

            $frames = CalendarDay::where('calendar_id', $course->calendar_id)
                ->whereBetween('date', [$from, $to])
                ->first();

            $holiday = Holiday::where('hospital_id', $course->hospital_id)
                ->whereBetween('date', [$from, $to])
                ->where('status', Status::VALID)
                ->first();

            $holiday_flg = 0;
            if ($holiday) {
                $holiday_flg = 1;
            }

            if (! $frames) {
                $results[] = [$from->format('Ymd'), 3, 0, $reserv_cnt, $holiday_flg];
                $from->addDay();
                $to->addDay();
                continue;
            }

            $appoint_status = 0;
            if ($frames->date->lt($reserv_enable_date)) {
                $appoint_status = 1;
            }

            if ($frames->date->gt($reserv_enableto_date)) {
                $appoint_status = 2;
            }

            $reserv_flg = 0;
            if ($frames->reservation_frames > $reserv_cnt) {
                $reserv_flg = 1;
            }

            $results[] = [$from->format('Ymd'), $appoint_status,  $reserv_cnt, $frames->reservation_frames, $holiday_flg];

            $from->addDay();
            $to->addDay();
        }

        return $results;
    }

}
