<?php

namespace App\Http\Controllers\Api;

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

        // 医療機関の検査コースのカレンダー取得
        $calendar_dailys = $this->getCourseWithCalendar($serach_condition);

        // response
        return new CalendarMonthlyResource($calendar_dailys);
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

        // 医療機関の検査コースのカレンダー取得
        $calendar_dailys = $this->getCourseWithCalendar($serach_condition);

        // response
        return new CalendarDailyResource($calendar_dailys);
    }

    /**
     * 医療機関の検査コースのカレンダー取得
     *
     * @param  検索条件  $serach_condition
     * @return 検索結果
     */
    private function getCourseWithCalendar($serach_condition)
    {
        $entity = Course::with([
            'calendar_days' => function ($query) use ($serach_condition) {
                $query->whereBetween(
                    'date',
                    [$serach_condition->get_yyyymmdd_from, $serach_condition->get_yyyymmdd_to]
                )
                    ->orderBy('date', 'asc');
            },
            'calendar_days.calendar',
            'hospital',
            'hospital.contract_information',
        ])
//            ->join('hospitals', 'hospitals.id', 'courses.hospital_id')
//            ->join('contract_informations', 'hospital_id', 'hospitals.id')
//            ->join('contract_informations', function ($query) use ($serach_condition) {
//                $query->on('contract_informations.hospital_id', '=', 'courses.hospital_id')
//                    ->where('contract_informations.code', '=', $serach_condition->hospital_code);
//            })
//            ->join('calendar_days', 'calendar_days.calendar_id', 'courses.calendar_id')
            ->whereHas('hospital.contract_information', function ($q) use ($serach_condition) {
                $q->where('contract_informations.code', $serach_condition->hospital_code);
            })
            ->find($serach_condition->course_no);

        foreach ($entity->calendar_days as $c) {

            // 既予約数取得
            $appoint_count = Reservation::where('hospital_id', $c->calendar->id)
                ->where('course_id', $serach_condition->course_no)
                ->whereIn('reservation_status', [1, 2, 3])
                ->whereDate('reservation_date', $c->date)->count();

            // 日毎受付可否情報
            $day = intval(date('Ymd', strtotime($c->date)));
            if ($day < $entity->reception_start_date)
                $c['appoint_status'] = 1; // 受付開始前
            else if ($day > $entity->reception_end_date || $c->reservation_frames <= $appoint_count)
                $c['appoint_status'] = 2; // 受付終了
            else if ($c->is_reservation_acceptance === 0)
                $c['appoint_status'] = 3; // 受付不可
            else
                $c['appoint_status'] = 0; // 受付可能

            // 既予約数取得
            $c['appoint_num'] = $appoint_count;

            // 休診日
            $c['closed_day'] = Holiday::where('hospital_id', $entity->hospital->id)
                ->whereDate('date', $c->date)->count();
        }
        return $entity;
    }

}
