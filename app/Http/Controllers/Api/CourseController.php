<?php

namespace App\Http\Controllers\Api;

use App\CalendarDay;
use App\Enums\Status;
use App\Http\Requests\CalendarMonthlyRequest;
use Illuminate\Http\Request;
use App\Http\Requests\CalendarDayRequest;

use App\Course;
use App\Reservation;
use App\Holiday;

use App\Http\Resources\CourseIndexResource;
use App\Http\Resources\CourseBasicResource;
use App\Http\Resources\CourseContentsResource;
use App\Http\Resources\CalendarMonthlyResource;
use App\Http\Resources\CalendarDailyResource;
use Carbon\Carbon;
use Log;

class CourseController extends ApiBaseController
{
    /**
     * 検査コース情報取得API
     *
     * @param  App\Http\Requests\CourseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $course_no = $request->input('course_no');
            $hospital_code = $request->input('hospital_code');

            $hospital_code_chk_result = $this->checkHospitalCode($hospital_code);
            $hospital_id = null;

            if (!$hospital_code_chk_result[0]) {
                return $this->createResponse($hospital_code_chk_result[1]);
            } else {
                $hospital_id = $hospital_code_chk_result[1];
            }

            $course_no_chk_result = $this->checkCourseNo($course_no, $hospital_id);

            if (!$course_no_chk_result[0]) {
                return $this->createResponse($course_no_chk_result[1]);
            }
            //検査コースコンテンツ情報取得
            $contents = $this->getCourseContents($hospital_id, $course_no);

            if (!$contents) {
                return $this->createResponse($this->messages['data_empty_error']);
            }

            return new CourseIndexResource($contents);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->createResponse($this->messages['system_error_db']);
        }
    }

    /**
     * 検査コース基本情報取得API
     *
     * @param  App\Http\Requests\CourseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function basic(Request $request)
    {
        try {
            $course_no = $request->input('course_no');
            $hospital_code = $request->input('hospital_code');

            $hospital_code_chk_result = $this->checkHospitalCode($hospital_code);
            $hospital_id = null;

            if (!$hospital_code_chk_result[0]) {
                return $this->createResponse($hospital_code_chk_result[1]);
            } else {
                $hospital_id = $hospital_code_chk_result[1];
            }

            $course_no_chk_result = $this->checkCourseNo($course_no, $hospital_id);

            if (!$course_no_chk_result[0]) {
                return $this->createResponse($course_no_chk_result[1]);
            }
            $basics = $this->basicCourse($hospital_id, $course_no);

            if (!$basics) {
                return $this->createResponse($this->messages['data_empty_error']);
            }

            return new CourseBasicResource($basics);

        } catch (\Exception $e) {
            Log::error($e);
            return $this->createResponse($this->messages['system_error_db']);
        }

    }

    /**
     * 検査コースコンテンツ情報取得API
     *
     * @param  App\Http\Requests\CourseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function contents(Request $request)
    {
        try {
            $hospital_code = $request->input('hospital_code');
            $course_no = $request->input('course_no');

            $hospital_code_chk_result = $this->checkHospitalCode($hospital_code);
            $hospital_id = null;

            if (!$hospital_code_chk_result[0]) {
                return $this->createResponse($hospital_code_chk_result[1]);
            } else {
                $hospital_id = $hospital_code_chk_result[1];
            }

            $course_no_chk_result = $this->checkCourseNo($course_no, $hospital_id);

            if (!$course_no_chk_result[0]) {
                return $this->createResponse($course_no_chk_result[1]);
            }

            $contents = $this->getCourseContents($hospital_id, $course_no);
            if (!$contents) {
                return $this->createResponse($this->messages['data_empty_error']);
            }

            return new CourseContentsResource($contents);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->createResponse($this->messages['system_error_db']);
        }
    }

    /**
     * 検査コース基本情報取得
     *
     * @param  $hospital_code
     * @param  $course_no
     * @return array
     */
    private function basicCourse($hospital_id, $course_no)
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
            ->where('id', $course_no)
            ->where('hospital_id', $hospital_id)
            ->get();
    }

    /**
     * 検査コースコンテンツ情報取得
     *
     * @param  $hospital_code
     * @param  $course_no
     * @return array
     */
    private function getCourseContents($hospital_id, $course_no)
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
            ->where('hospital_id', $hospital_id)
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
//        try {
            // 検索条件取得
            $serach_condition = $request->toObject();

            $hospital_code_chk_result = $this->checkHospitalCode($serach_condition->hospital_code);
            $hospital_id = null;

            if (!$hospital_code_chk_result[0]) {
                return $this->createResponse($hospital_code_chk_result[1]);
            } else {
                $hospital_id = $hospital_code_chk_result[1];
            }

            $course_no_chk_result = $this->checkCourseNo($serach_condition->course_no, $hospital_id);

            if (!$course_no_chk_result[0]) {
                return $this->createResponse($course_no_chk_result[1]);
            }

            if (!empty($request->input('get_yyyymm_from'))) {
                $from_chk_result = $this->checkMonthDate($serach_condition->get_yyyymm_from);
                if (!$from_chk_result[0]) {
                    return $this->createResponse($from_chk_result[1]);
                }
            }

            if (!empty($request->input('get_yyyymm_to'))) {
                $to_chk_result = $this->checkMonthDate($serach_condition->get_yyyymm_to);
                if (!$to_chk_result[0]) {
                    return $this->createResponse($to_chk_result[1]);
                }
            }

            $course = Course::find($serach_condition->course_no);

            // 医療機関の検査コースのカレンダー取得
            $month_data = $this->getMonthReservationEnableInfo($serach_condition, $course);

            if (!$month_data) {
                return $this->createResponse($this->messages['data_empty_error']);
            }

            $data = ['search_cond' => $serach_condition,  'course' => $course, 'month_data' => $month_data];

            // response
            return new CalendarMonthlyResource($data);
//        } catch (\Throwable $e) {
//            Log::error($e);
//            return $this->createResponse($this->messages['system_error_db']);
//        }
    }

    /**
     * 検査コース空満情報（日別）取得API
     *
     * @param  App\Http\Requests\CalendarDayRequest
     * @return \Illuminate\Http\Response
     */
    public function calendar_daily(CalendarDayRequest $request)
    {
        try {
            // 検索条件取得
            $serach_condition = $request->toObject();

            $hospital_code_chk_result = $this->checkHospitalCode($serach_condition->hospital_code);
            $hospital_id = null;

            if (!$hospital_code_chk_result[0]) {
                return $this->createResponse($hospital_code_chk_result[1]);
            } else {
                $hospital_id = $hospital_code_chk_result[1];
            }

            $course_no_chk_result = $this->checkCourseNo($serach_condition->course_no, $hospital_id);

            if (!$course_no_chk_result[0]) {
                return $this->createResponse($course_no_chk_result[1]);
            }

            if (!empty($request->input('get_yyyymmdd_from'))) {
                $from_chk_result = $this->checkDayDate($request->input('get_yyyymmdd_from'));
                if (!$from_chk_result[0]) {
                    return $this->createResponse($from_chk_result[1]);
                }
            }

            if (!empty($request->input('get_yyyymmdd_to'))) {
                $to_chk_result = $this->checkDayDate($request->input('get_yyyymmdd_to'));
                if (!$to_chk_result[0]) {
                    return $this->createResponse($to_chk_result[1]);
                }
            }

            $course = Course::find($serach_condition->course_no);

            // 医療機関の検査コースのカレンダー取得
            $calendar_dailys = $this->getDayReservationEnableInfo($serach_condition, $course);

            $data = ['search_cond' => $serach_condition,  'course' => $course, 'day_data' => $calendar_dailys];

            // response
            return new CalendarDailyResource($data);
        } catch (\Throwable $e) {
            Log::error($e);
            return $this->createResponse($this->messages['system_error_db']);
        }
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
