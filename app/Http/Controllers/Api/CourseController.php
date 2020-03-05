<?php

namespace App\Http\Controllers\Api;

use App\CalendarDay;
use App\Enums\GenderTak;
use App\Enums\HonninKbn;
use App\Enums\ReservationStatus;
use App\Enums\Status;
use App\Hospital;
use App\Http\Requests\CalendarMonthlyRequest;
use App\Http\Resources\CalendarBaseResource;
use App\Reservation;
use Illuminate\Http\Request;
use App\Http\Requests\CalendarDayRequest;
use App\Http\Requests\CourseRequest;
use App\Course;
use App\ContractInformation;
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
    public function index(CourseRequest $request)
    {
        try {

            $course_code = $request->input('course_code');

            $hospital_id = ContractInformation::where('code', $request->input('hospital_code'))->first()->hospital_id;

            // //検査コースコンテンツ情報取得
            $course = $this->getCourseContents($hospital_id, $course_code);

            if (!$course) {
                return $this->createResponse($this->messages['data_empty_error'], $request->input('callback'));
            }
            // その他コース情報取得
            $courses = $this->getCourses($hospital_id, $course->id);
            $hospital = $this->getHospitalData($course->hospital_id);
            $data = ['course' => $course, 'courses' => $courses, 'hospital' => $hospital];

            return new CourseIndexResource($data);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->createResponse($this->messages['system_error_db'], $request->input('callback'));
        }
    }

    /**
     * 検査コース基本情報取得API
     *
     * @param  App\Http\Requests\CourseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function basic(CourseRequest $request)
    {
        try {

            $course_code = $request->input('course_code');
            $hospital_id = ContractInformation::where('code', $request->input('hospital_code'))->first()->hospital_id;

            // //検査コース基本情報取得
            $course = $this->getCourseBasic($hospital_id, $course_code, $request);

            if (!$course) {
                return $this->createResponse($this->messages['data_empty_error'], $request->input('callback'));
            }

            // その他コース情報取得
            $hospital = $this->getHospitalData($course->hospital_id);
            $data = ['course' => $course,  'hospital' => $hospital];

            if (!empty($request->input('sex'))) {
                $course->kenshin_relation_flg = true;
                $course->medical_exam_sys_id = $hospital->medical_examination_system_id;
                $course->reservation_date = $request->input('reservation_date');
                $course->sex = $request->input('sex');
                $course->birth = $request->input('birth');
                $course->honnin_kbn = $request->input('honnin_kbn');
            }

            return new CourseBasicResource($data);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->createResponse($this->messages['system_error_db'], $request->input('callback'));
        }
    }

    /**
     * 検査コースコンテンツ情報取得API
     *
     * @param  App\Http\Requests\CourseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function contents(CourseRequest $request)
    {
        try {

            $course_code = $request->input('course_code');
            $hospital_id = ContractInformation::where('code', $request->input('hospital_code'))->first()->hospital_id;

            // //検査コースコンテンツ情報取得
            $course = $this->getCourseContents($hospital_id, $course_code);

            if (!$course) {
                return $this->createResponse($this->messages['data_empty_error'], $request->input('callback'));
            }
            // その他コース情報取得
            $hospital = $this->getHospitalData($course->hospital_id);
            $data = ['course' => $course, 'hospital' => $hospital];

            return new CourseContentsResource($data);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->createResponse($this->messages['system_error_db'], $request->input('callback'));
        }
    }

    /**
     * 検査コースコンテンツ情報取得
     *
     * @param  $hospital_code
     * @param  $course_no
     * @return Course
     */
    private function getCourseBasic($hospital_id, $course_code, $request)
    {
        $today = Carbon::today()->toDateString();
        $query = Course::with([
            'course_images',
            'course_details' => function ($query) {
                $query->whereIn('major_classification_id', [2, 3, 4, 5, 6, 11, 13, 15, 16, 17, 18, 19, 20, 24, 25])
                    ->orderBy('major_classification_id')
                    ->orderBy('middle_classification_id')
                    ->orderBy('minor_classification_id')
                ;},
            'course_details.major_classification',
            'course_details.middle_classification',
            'course_details.minor_classification' => function ($query) {
                $query->where('status', Status::VALID);
            },
            'contract_information'
        ])
            ->where('hospital_id', $hospital_id)
            ->where('code', $course_code)
            ->where('is_category', 0)
            ->where('web_reception', 0)
            ->where('publish_start_date', '<=', $today)
            ->where('publish_end_date', '>=', $today);
        $query->with(['course_options']);

        if (!empty($request->input('sex'))) {
            $query->with([
                'kenshin_sys_courses',
                'kenshin_sys_courses.course_futan_conditions' => function ($q) use ($request) {
                    $q->whereIn('sex', [$request->input('sex'), GenderTak::ALL])
                        ->whereIn('honnin_kbn', [$request->input('honnin_kbn'), HonninKbn::ALL]);

                },
                'kenshin_sys_courses.kenshin_sys_options',
                'kenshin_sys_courses.kenshin_sys_options.option_futan_conditions' => function ($q) use ($request) {
                    $q->whereIn('sex', [$request->input('sex'), GenderTak::ALL])
                        ->whereIn('honnin_kbn', [$request->input('honnin_kbn'), HonninKbn::ALL])
                        ->orderBy('yusen_kbn');
                    },
                'kenshin_sys_courses.kenshin_sys_options.option_futan_conditions.option_target_ages'
                ]);
        }

        return $query->first();
    }

    /**
     * 検査コースコンテンツ情報取得
     *
     * @param  $hospital_code
     * @param  $course_no
     * @return Course
     */
    private function getCourseContents($hospital_id, $course_code)
    {
        $today = Carbon::today()->toDateString();
        $end_day = Carbon::today()->addMonthsNoOverflow(5)->endOfMonth()->toDateString();
        return Course::with([
            'course_images',
            'course_details' => function ($query) {
                $query->whereIn('major_classification_id', [2, 3, 4, 5, 6, 11, 13, 15, 16, 17, 18, 19, 20, 22, 24, 25])
                ->orderBy('major_classification_id')
                ->orderBy('middle_classification_id')
                ->orderBy('minor_classification_id')
                ;},
            'course_details.major_classification',
            'course_details.middle_classification',
            'course_details.minor_classification' => function ($query) {
            $query->where('status', Status::VALID);
            },
            'calendar_days' => function ($query) use ($today, $end_day) {
                $query->where('date', '>=', $today)
                    ->where('date', '<=', $end_day)
                    ->orderBy('date');
            },
            'course_options',
            'course_options.option',
            'course_questions',
            'contract_information'
        ])
            ->where('hospital_id', $hospital_id)
            ->where('code', $course_code)
            ->where('is_category', 0)
            ->where('web_reception', 0)
            ->where('publish_start_date', '<=', $today)
            ->where('publish_end_date', '>=', $today)
            ->first();
    }

    /**
     * @param $hospital_id
     * @param $course_id
     */
    private function getCourses($hospital_id, $course_id) {

        $today = Carbon::today()->toDateString();
        return Course::with([
            'course_images',
            'course_details' => function ($query) {
                $query->whereIn('major_classification_id', [2, 3, 4, 5, 6])
                    ->orderBy('major_classification_id')
                    ->orderBy('middle_classification_id')
                    ->orderBy('minor_classification_id');
                },
            'course_details.minor_classification',
            'contract_information',
            'calendar',
        ])
            ->where('hospital_id', $hospital_id)
            ->where('courses.id', '<>', $course_id)
            ->where('is_category', 0)
            ->where('web_reception', 0)
            ->where('publish_start_date', '<=', $today)
            ->where('publish_end_date', '>=', $today)
            ->get();
    }

    /**
     * 医療機関情報取得
     *
     * @param	string	$hospital_id	医療施設ID
     * @return	object					医療期間情報
     **/
    private function getHospitalData($hospital_id)
    {
        return Hospital::with([
            'contract_information',
            'hospital_details',
            'hospital_details.minor_classification',
            'medical_treatment_times',
            'hospital_categories'  => function ($query) {
                $query->orderBy('image_order')
                    ->orderBy('file_location_no');
            },
            'hospital_categories.image_order',
            'hospital_categories.hospital_image'
        ])
            ->find($hospital_id);
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

            $hospital_id = ContractInformation::where('code', $request->input('hospital_code'))->first()->hospital_id;

            $course = Course::where('code', $serach_condition->course_code)
                ->where('hospital_id', $hospital_id)
                ->where('is_category', 0)
                ->first();

            if (!$course) {
                return $this->createResponse($this->messages['system_error_db'], $request->input('callback'));
            }

            // 医療機関の検査コースのカレンダー取得
            $month_data = $this->getMonthReservationEnableInfo($serach_condition, $course);

            if (!$month_data) {
                return $this->createResponse($this->messages['system_error_db'], $request->input('callback'));
            }

            $data = ['search_cond' => $serach_condition,  'course' => $course, 'month_data' => $month_data];

            // response
            return new CalendarMonthlyResource($data);
        } catch (\Throwable $e) {
            Log::error($e);
            return $this->createResponse($this->messages['system_error_db'], $request->input('callback'));
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

            $hospital_id = ContractInformation::where('code', $request->input('hospital_code'))->first()->hospital_id;

            $from = $serach_condition->get_yyyymmdd_from;
            $to = $serach_condition->get_yyyymmdd_to;

            $query = Course::with([
                'calendar_days' => function ($query) use ($from, $to) {
                    $query->where('date', '>=', $from)
                        ->where('date', '<=', $to)
                        ->orderBy('date');
                },
            ])
                ->where('code', $serach_condition->course_code)
                ->where('hospital_id', $hospital_id)
                ->where('is_category', 0);

            if (!empty($request->input('sex'))) {
                $query->with([
                    'kenshin_sys_courses',
                    'kenshin_sys_courses.course_futan_conditions' => function ($q) use ($request) {
                        $q->whereIn('sex', [$request->input('sex'), GenderTak::ALL])
                            ->whereIn('honnin_kbn', [$request->input('honnin_kbn'), HonninKbn::ALL]);

                    }]);
            }

            $course = $query->first();

            if (!$course) {
                return $this->createResponse($this->messages['data_empty_error'], $request->input('callback'));
            }

            if (!empty($request->input('sex'))) {
                $course->kenshin_relation_flg = true;
                $course->sex = $request->input('sex');
                $course->birth = $request->input('birth');
                $course->honnin_kbn = $request->input('honnin_kbn');
            }

            $data = ['hospital_id' => $hospital_id, 'hospital_code' => $serach_condition->hospital_code,  'course' => $course];

            // response
            return new CalendarBaseResource($data);
        } catch (\Throwable $e) {
            
           Log::error($e);
           return $this->createResponse($this->messages['system_error_db'], $request->input('callback'));
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

        $calendar_days = CalendarDay::where('calendar_id', $course->calendar_id)
            ->where('date', '>=', $from)
            ->where('date', '<=', $to)
            ->get();

        return $calendar_days;

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
