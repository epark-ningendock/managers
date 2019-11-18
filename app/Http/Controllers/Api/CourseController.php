<?php

namespace App\Http\Controllers\Api;

use App\CalendarDay;
use App\Enums\ReservationStatus;
use App\Http\Requests\CalendarMonthlyRequest;
use App\Reservation;
use Illuminate\Http\Request;
use App\Http\Requests\CalendarDayRequest;
use App\Course;

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
            $course_code = $request->input('course_code');
            $hospital_code = $request->input('hospital_code');

            $hospital_code_chk_result = $this->checkHospitalCode($hospital_code);
            $hospital_id = null;

            if (!$hospital_code_chk_result[0]) {
                return $this->createResponse($hospital_code_chk_result[1]);
            } else {
                $hospital_id = $hospital_code_chk_result[1];
            }

            $course_no_chk_result = $this->checkCourseCode($course_code, $hospital_id);

            if (!$course_no_chk_result[0]) {
                return $this->createResponse($course_no_chk_result[1]);
            }
            //検査コースコンテンツ情報取得
            $contents = $this->getCourseContents($hospital_id, $course_code);

            if (!$contents) {
                return $this->createResponse($this->messages['data_empty_error']);
            }
            // 月空満情報取得
            $from = Carbon::today()->format("Y-m-s");
            $to = Carbon::today()->addMonthsNoOverflow(2)->endOfMonth();
            $search_condition = (object) [
                'get_yyyymmdd_from' => $from,
                'get_yyyymmdd_to' => $to,
            ];
            $monthly_data = $this->getMonthReservationEnableInfo($search_condition, $contents);
            $daily_data = $this->getDayReservationEnableInfo($search_condition, $contents);

            $data = ['course' => $contents, 'monthly_data' => $monthly_data, 'daily_data' => $daily_data];

            return new CourseIndexResource($data);
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
            $course_code = $request->input('course_code');
            $hospital_code = $request->input('hospital_code');

            $hospital_code_chk_result = $this->checkHospitalCode($hospital_code);
            $hospital_id = null;

            if (!$hospital_code_chk_result[0]) {
                return $this->createResponse($hospital_code_chk_result[1]);
            } else {
                $hospital_id = $hospital_code_chk_result[1];
            }

            $course_no_chk_result = $this->checkCourseCode($course_code, $hospital_id);

            if (!$course_no_chk_result[0]) {
                return $this->createResponse($course_no_chk_result[1]);
            }
            $basics = $this->basicCourse($hospital_id, $course_code);

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
            $course_code = $request->input('course_code');

            $hospital_code_chk_result = $this->checkHospitalCode($hospital_code);
            $hospital_id = null;

            if (!$hospital_code_chk_result[0]) {
                return $this->createResponse($hospital_code_chk_result[1]);
            } else {
                $hospital_id = $hospital_code_chk_result[1];
            }

            $course_no_chk_result = $this->checkCourseCode($course_code, $hospital_id);

            if (!$course_no_chk_result[0]) {
                return $this->createResponse($course_no_chk_result[1]);
            }

            $contents = $this->getCourseContents($hospital_id, $course_code);
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
    private function basicCourse($hospital_id, $course_code)
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
            ->where('code', $course_code)
            ->where('hospital_id', $hospital_id)
            ->where('is_category', 0)
            ->get();
    }

    /**
     * 検査コースコンテンツ情報取得
     *
     * @param  $hospital_code
     * @param  $course_no
     * @return array
     */
    private function getCourseContents($hospital_id, $course_code)
    {
        $data = Course::with([
            'course_details',
            'course_details.major_classification',
            'course_details.major_classification.middle_classifications',
            'course_details.major_classification.middle_classifications.minor_classifications',
            'course_options',
            'course_options.option',
            'course_questions',
            'hospital',
            'hospital.contract_information',
            'hospital.hospital_categories.image_order',
            'hospital.hospital_categories.hospital_image'
        ])
            ->where('hospital_id', $hospital_id)
            ->where('code', $course_code)
            ->where('is_category', 0)
            ->first();

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

            $course_no_chk_result = $this->checkCourseCode($serach_condition->course_code, $hospital_id);

            if (!$course_no_chk_result[0]) {
                return $this->createResponse($course_no_chk_result[1]);
            }

            if (!empty($request->input('get_yyyymm_from'))) {
                $from_chk_result = $this->checkMonthDate($request->input('get_yyyymm_from'));
                if (!$from_chk_result[0]) {
                    return $this->createResponse($from_chk_result[1]);
                }
            }

            if (!empty($request->input('get_yyyymm_to'))) {
                $to_chk_result = $this->checkMonthDate($request->input('get_yyyymm_to'));
                if (!$to_chk_result[0]) {
                    return $this->createResponse($to_chk_result[1]);
                }
            }

            $course = Course::where('code', $serach_condition->course_code)
                ->where('hospital_id', $hospital_id)
                ->where('is_category', 0)
                ->first();

            if (!$course) {
                return $this->createResponse($this->messages['data_empty_error']);
            }

            // 医療機関の検査コースのカレンダー取得
            $month_data = $this->getMonthReservationEnableInfo($serach_condition, $course);

            if (!$month_data) {
                return $this->createResponse($this->messages['data_empty_error']);
            }

            $data = ['search_cond' => $serach_condition,  'course' => $course, 'month_data' => $month_data];

            // response
            return new CalendarMonthlyResource($data);
        } catch (\Throwable $e) {
            Log::error($e);
            return $this->createResponse($this->messages['system_error_db']);
        }
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

            $course_no_chk_result = $this->checkCourseCode($serach_condition->course_code, $hospital_id);

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

            $course = Course::where('code', $serach_condition->course_code)
                ->where('hospital_id', $hospital_id)
                ->where('is_category', 0)
                ->first();

            if (!$course) {
                return $this->createResponse($this->messages['data_empty_error']);
            }

            // 医療機関の検査コースのカレンダー取得
            $calendar_dailys = $this->getDayReservationEnableInfo($serach_condition, $course);

            if (!$calendar_dailys) {
                return $this->createResponse($this->messages['data_empty_error']);
            }

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

        $monthly_wakus = CalendarDay::where('calendar_id', $course->calendar_id)
            ->where('date', '>=', $serach_condition->get_yyyymmdd_from)
            ->where('date', '<=', $serach_condition->get_yyyymmdd_to)
            ->where('is_holiday', 0)
            ->where('is_reservation_acceptance', 1)
            ->get()
            ->groupBy(function ($row) {
                return $row->date->format('m');
            })
            ->map(function ($day) {
                return collect([$day->sum('reservation_frames'), $day->sum('reservation_count'), $day[0]->date->format('Ym')]);
            });

        $results = [];
        foreach ($monthly_wakus as $monthly_waku) {
            $appoint_ok = 0;
            if ($monthly_waku[0] > $monthly_waku[1]) {
                $appoint_ok = 1;
            }
            $results[] = [$monthly_waku[2], $appoint_ok];

        }

        return $results;
    }

    /**
     * 日次予約可否情報を返す
     * @param $serach_condition
     */
    private function getDayReservationEnableInfo($serach_condition, $course) {

        $from = $serach_condition->get_yyyymmdd_from;
        $to = $serach_condition->get_yyyymmdd_to;

        $reserv_enable_date = Carbon::today()->subMonth(floor($course->reception_start_date / 1000))->subDay($course->reception_start_date % 1000);
        $reserv_enableto_date = Carbon::today()->addMonth(floor($course->reception_end_date / 1000))->addDay($course->reception_end_date % 1000);

        $calendar_days = CalendarDay::where('calendar_id', $course->calendar_id)
            ->where('date', '>=', $from)
            ->where('date', '<=', $to)
            ->get();

        $results = [];

        foreach ($calendar_days as $calendar_day) {
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

            $results[] = [$calendar_day->date->format('Ymd'), $appoint_status,  $calendar_day->reservation_count, $calendar_day->reservation_frames, $holiday_flg];

        }

        return $results;
    }
}
