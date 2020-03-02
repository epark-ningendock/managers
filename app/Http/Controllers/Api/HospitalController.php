<?php

namespace App\Http\Controllers\Api;

use App\Enums\Gender;
use App\Enums\GenderTak;
use App\Enums\HonninKbn;
use App\Enums\ReservationStatus;
use App\Enums\Status;
use App\Hospital;
use App\Http\Resources\HospitalAccessResource;
use App\Http\Resources\HospitalReservationFramesResource;
use Illuminate\Http\Request;
use App\Http\Requests\HospitalRequest;
use App\Http\Requests\HospitalFrameRequest;
use App\ContractInformation;

use App\Http\Resources\HospitalIndexResource;
use App\Http\Resources\HospitalBasicResource;
use App\Http\Resources\HospitalCoursesResource;
use App\Http\Resources\HospitalContentsResource;
use App\Http\Resources\HospitalReleaseResource;
use App\Http\Resources\HospitalReleaseCourseResource;
use App\Http\Resources\HospitalReserveCntBaseResource;

use Carbon\Carbon;
use Log;

class HospitalController extends ApiBaseController
{
    /**
     * 医療機関情報取得API
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(HospitalRequest $request)
    {
        try {
            $hospital_id = ContractInformation::where('code', $request->input('hospital_code'))->first()->hospital_id;

            $hospital = $this->getHospitalData($hospital_id, $request);
            if (!empty($request->input('sex'))) {
                $hospital->setKenshinRelation(true,
                    $request->input('sex'),
                    $request->input('birth'),
                    $request->input('honnin_kbn'));
            }

            return new HospitalIndexResource($hospital);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->createResponse($this->messages['system_error_db'], $request->input('callback'));
        }
    }

    /**
     * 医療機関検査コース一覧情報取得API
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function courses(HospitalRequest $request)
    {
        try {

            $hospital_id = ContractInformation::where('code', $request->input('hospital_code'))->first()->hospital_id;

            $hospital = $this->getHospitalData($hospital_id, $request);
            if (!empty($request->input('sex'))) {
                $hospital->setKenshinRelation(true,
                    $request->input('sex'),
                    $request->input('birth'),
                    $request->input('honnin_kbn'));
            }

            return new HospitalCoursesResource($hospital);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->createResponse($this->messages['system_error_db'], $request->input('callback'));
        }
    }

    /**
     * 医療機関空き枠一覧情報取得API
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function frame(HospitalFrameRequest $request)
    {
        try {
            $get_from_yyyymmdd = $request->input('get_yyyymmdd_from');
            $get_to_yyyymmdd = $request->input('get_yyyymmdd_to');

            $hospital_id = ContractInformation::where('code', $request->input('hospital_code'))->first()->hospital_id;

            return new HospitalReservationFramesResource($this->getHospitalFrames($hospital_id, $get_from_yyyymmdd, $get_to_yyyymmdd));
        } catch (\Exception $e) {
            Log::error($e);
            return $this->createResponse($this->messages['system_error_db'], $request->input('callback'));
        }
    }

    /**
     * 医療機関コンテンツ情報取得API
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function contents(HospitalRequest $request)
    {
        try {

            $hospital_id =  ContractInformation::where('code', $request->input('hospital_code'))->first()->hospital_id;

            return new HospitalContentsResource($this->getContent($hospital_id));
        } catch (\Exception $e) {
            Log::error($e);
            return $this->createResponse($this->messages['system_error_db'], $request->input('callback'));
        }
    }

    /**
     * 公開中医療機関コード取得API
     *
     * @param  App\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function release(Request $request)
    {
        try {
            return new HospitalReleaseResource($this->getReleaseHospital());
        } catch (\Exception $e) {
            Log::error($e);
            return $this->createResponse($this->messages['system_error_db'], $request->input('callback'));
        }

    }

    /**
     * 公開中医療機関コース取得API
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function release_course(Request $request)
    {
        try {
            return new HospitalReleaseCourseResource($this->getReleaseCourses());
        } catch (\Exception $e) {
            Log::error($e);
            return $this->createResponse($this->messages['system_error_db'], $request->input('callback'));
        }
    }



    /**
     * 医療機関手数料利率取得API
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function fee_rate(HospitalRequest $request){
        try {
            $hospital_code = $request->input('hospital_code');
            $hospital_id = ContractInformation::where('code', $request->input('hospital_code'))->first()->hospital_id;
            $hospitalPlan = HospitalPlan::where('hospital_id', $hospital_id)
            ->whereDate('from', '<=', Carbon::today())
            ->where('hospital_id', $hospital_id)
            ->where(function($q) {
                $q->whereDate('to', '>=', Carbon::today())
                    ->orWhere('to', '=', null);
            })->get()->first();

            return new HospitalFeerateResource($hospitalPlan->contractPlan);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->createResponse($this->messages['system_error_db'], $request->input('callback'));
        }
    }
    /**
     * 医療機関アクセス情報取得API
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function access(HospitalRequest $request)
    {
        try { 
            $hospital_code = $request->input('hospital_code');

            $hospital_id = ContractInformation::where('code', $request->input('hospital_code'))->first()->hospital_id;
           
            return new HospitalAccessResource($this->getAccessData($hospital_id));
        } catch (\Exception $e) {
            Log::error($e);
            return $this->createResponse($this->messages['system_error_db'], $request->input('callback'));
        }
    }

    /**
     * 医療機関情報取得
     *
     * @param	string	$hospital_id	医療施設ID
     * @return	object					医療期間情報
     **/
    private function getHospitalData($hospital_id, $request)
    {
        $today = Carbon::today()->toDateString();
        $from = Carbon::today()->toDateString();
        $to = Carbon::today()->addMonthsNoOverflow(5)->endOfMonth()->toDateString();
        $query = Hospital::with([
            'contract_information',
            'hospital_details',
            'hospital_details.minor_classification',
            'medical_treatment_times',
            'hospital_categories'  => function ($query) {
                $query->orderBy('image_order')
                    ->orderBy('file_location_no');
            },
            'hospital_categories.image_order',
            'hospital_categories.hospital_image',
            'courses' => function ($query) use ($today) {
                $query->where('is_category', 0)
                    ->where('web_reception', 0)
                    ->where('publish_start_date', '<=', $today)
                    ->where('publish_end_date', '>=', $today)
                    ->orderBy('courses.order');
            },
            'courses.course_details' => function ($query) {
                $query->whereIn('major_classification_id', [2, 3, 4, 5, 6, 11, 13, 15, 19, 24]);
            },
            'courses.course_details.minor_classification' => function ($query) {
                $query->orderBy('order');
            },
            'courses.course_images.hospital_image',
            'courses.calendar_days' => function ($query) use ($from, $to) {
                $query->where('date', '>=', $from)
                    ->where('date', '<=', $to)
                    ->orderBy('date');
            },
        ]);

        if (!empty($request->input('sex'))) {
            $query->with([
                'courses' => function ($query) use ($today) {
                    $query->where('is_category', 0)
                        ->where('web_reception', 0)
                        ->where('publish_start_date', '<=', $today)
                        ->where('publish_end_date', '>=', $today)
                        ->orderBy('courses.order');
                },
                'courses.kenshin_sys_courses',
                'courses.kenshin_sys_courses.course_futan_conditions' => function ($q) use ($request) {
                    $q->whereIn('sex', [$request->input('sex'), GenderTak::ALL])
                    ->whereIn('honnin_kbn', [$request->input('honnin_kbn'), HonninKbn::ALL]);

            }]);
        }

//        $query->whereHas('courses' , function($q) use ($today) {
//            $q->where('is_category', 0)
//                ->where('web_reception', 0)
//                ->where('publish_start_date', '<=', $today)
//                ->where('publish_end_date', '>=', $today)
//            ;
//        });

            return $query->find($hospital_id);
    }

    /**
     * @param $hospital_id
     * @param $get_from_date
     * @param $get_to_date
     */
    private function getHospitalFrames($hospital_id, $get_from_date, $get_to_date) {

        $today = Carbon::today()->toDateString();
        $from_date = Carbon::createMidnightDate(
            substr($get_from_date, 0, 4),
            substr($get_from_date, 4, 2),
            substr($get_from_date, 6, 2))->toDateString();

        $to_date = Carbon::createMidnightDate(
            substr($get_to_date, 0, 4),
            substr($get_to_date, 4, 2),
            substr($get_to_date, 6, 2))->toDateString();

        return Hospital::with([
            'hospital_categories' => function ($query) {
                $query->whereIn('image_order', [3, 4]);
            },
            'contract_information',
            'courses'=> function ($query) use ($today) {
            $query->where('is_category', 0)
                ->where('web_reception', 0)
                ->where('publish_start_date', '<=', $today)
                ->where('publish_end_date', '>=', $today)
                ->orderBy('courses.order');
        },
            'courses.course_details' => function ($query) {
                $query->whereIn('major_classification_id', [11, 13]);
            },
            'courses.course_details.minor_classification',
            'courses.calendar_days' => function ($query) use ($from_date, $to_date) {
                $query->where('date', '>=', $from_date)
                ->where('date', '<=', $to_date);
            },
        ])
            ->whereHas('courses' , function($q) use ($today) {
                $q->where('courses.is_category', 0)
                    ->where('web_reception', 0)
                    ->where('publish_start_date', '<=', $today)
                    ->where('publish_end_date', '>=', $today)
                ;
            })
            ->find($hospital_id);

    }

    /**
     * 医療機関コースコンテンツ取得
     *
     * @param  string $hospital_id 医療機関ID
     * @return Illuminate\Support\Collection 整形結果
     */
    private function getContent($hospital_id)
    {
        $today = Carbon::today()->toDateString();
        $entity = Hospital::with([
            'contract_information',
            'hospital_categories' => function ($query) {
                $query->whereIn('image_order', [3, 4, 6, 7, 8]);
                $query->orderBy('order2');
            },
            'hospital_categories.hospital_image',
            'hospital_categories.interview_details',
            'courses' => function ($query) use ($today) {
                $query->where('is_category', 0)
                        ->where('web_reception', 0)
                        ->where('publish_start_date', '<=', $today)
                        ->where('publish_end_date', '>=', $today)
                        ->orderBy('courses.order');
            },
            'courses.course_details' => function ($query) {
                $query->whereIn('major_classification_id', [2, 3, 4, 5, 6, 11, 13, 15, 19, 24]);
            },
            'courses.course_details.minor_classification' => function ($query) {
                $query->orderBy('order');
            },
        ])
            ->find($hospital_id);

        return $entity;
    }

    /**
     * 医療機関アクセス取得
     *
     * @param  string $hospital_id 医療機関ID
     * @return Hospital
     */
    private function getAccessData($hospital_id)
    {
        $entity = Hospital::with([
            'contract_information',
            'hospital_categories' => function ($query) {
                $query->whereIn('image_order', [3, 4]);
                $query->orderBy('order2');
            },
            'medical_treatment_times',
        ])
            ->find($hospital_id);

        return $entity;
    }

    /**
     * 公開中医療機関情報取得
     */
    private function getReleaseHospital() {

        return Hospital::join('contract_informations', 'contract_informations.hospital_id', 'hospitals.id')
            ->where('status', Status::VALID)
            ->pluck('contract_informations.code');
    }

 /**
     * 公開中コース情報取得
     */
    private function getReleaseCourses() {

        $hospitals = Hospital::with([
            'contract_information',
            'courses'
        ])
            ->whereHas('courses', function ($q) {
                $q->where('publish_start_date', '<=', Carbon::today())
                    ->orWhereNull('publish_start_date');
                $q->where('publish_end_date', '>=', Carbon::today())
                    ->orWhereNull('publish_end_date');
                $q->where('status', Status::VALID);
                $q->where('is_category', 0);
            })
            ->where('status', Status::VALID)
            ->get();

        $results = [];
        foreach ($hospitals as $hospital) {
            if (!isset($hospital->contract_information)) {
                continue;
            }
            $course_data = [];
            foreach ($hospital->courses as $course) {
                $course_data[] = ['course_code' => $course->code, 'course_no' => $course->id];
            }
            $results[] = ['hospital_code' => $hospital->contract_information->code, 'courses' => $course_data];
        }

        return $results;
    }
}
