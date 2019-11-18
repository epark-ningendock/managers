<?php

namespace App\Http\Controllers\Api;

use App\Enums\ReservationStatus;
use App\Enums\Status;
use App\Hospital;
use Illuminate\Http\Request;

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
    public function index(Request $request)
    {
        try {
            $hospital_code = $request->input('hospital_code');

            $hospital_code_chk_result = $this->checkHospitalCode($hospital_code);
            $hospital_id = null;

            if (!$hospital_code_chk_result[0]) {
                return $this->createResponse($hospital_code_chk_result[1]);
            } else {
                $hospital_id = $hospital_code_chk_result[1];
            }

            return new HospitalIndexResource($this->getHospitalData($hospital_id));
        } catch (\Exception $e) {
            Log::error($e);
            return $this->createResponse($this->messages['system_error_db']);
        }
    }

    /**
     * 医療機関基本情報取得API
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function basic(Request $request)
    {
        try {
            $hospital_code = $request->input('hospital_code');

            $hospital_code_chk_result = $this->checkHospitalCode($hospital_code);
            $hospital_id = null;

            if (!$hospital_code_chk_result[0]) {
                return $this->createResponse($hospital_code_chk_result[1]);
            } else {
                $hospital_id = $hospital_code_chk_result[1];
            }
            return new HospitalBasicResource($this->getHospitalData($hospital_id));

        } catch (\Exception $e) {
            Log::error($e);
            return $this->createResponse($this->messages['system_error_db']);
        }
    }

    /**
     * 医療機関検査コース一覧情報取得API
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function courses(Request $request)
    {
        try {
            $hospital_code = $request->input('hospital_code');

            $hospital_code_chk_result = $this->checkHospitalCode($hospital_code);
            $hospital_id = null;

            if (!$hospital_code_chk_result[0]) {
                return $this->createResponse($hospital_code_chk_result[1]);
            } else {
                $hospital_id = $hospital_code_chk_result[1];
            }
            return new HospitalCoursesResource($this->getHospitalData($hospital_id));
        } catch (\Exception $e) {
            Log::error($e);
            return $this->createResponse($this->messages['system_error_db']);
        }
    }

    /**
     * 医療機関コンテンツ情報取得API
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function contents(Request $request)
    {
        try {
            $hospital_code = $request->input('hospital_code');

            $hospital_code_chk_result = $this->checkHospitalCode($hospital_code);
            $hospital_id = null;

            if (!$hospital_code_chk_result[0]) {
                return $this->createResponse($hospital_code_chk_result[1]);
            } else {
                $hospital_id = $hospital_code_chk_result[1];
            }
            return new HospitalContentsResource($this->getContent($hospital_id));
        } catch (\Exception $e) {
            Log::error($e);
            return $this->createResponse($this->messages['system_error_db']);
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
            return $this->createResponse($this->messages['system_error_db']);
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
            $data = ['hospital_code' => $request->input('hospital_code')];
            return new HospitalReleaseCourseResource($data);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->createResponse($this->messages['system_error_db']);
        }

    }

    /**
     * 医療機関予約数取得API
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reserve_cnt(Request $request)
    {
//        try {
            $data = ['data' => $this->getReserveCnt($request->input('hospital_no'))];
            return new HospitalReserveCntBaseResource($data);
//        } catch (\Exception $e) {
//            Log::error($e);
//            return $this->createResponse($this->messages['system_error_db']);
//        }
    }

    /**
     * 医療機関情報取得
     *
     * @param	object	$objDb			データベースオブジェクト
     * @param	string	$sHospitalNo	医療施設番号
     * @return	object					TOPページ情報
     **/
    public static function getHospitalData($hospital_id)
    {
        $from_date = Carbon::today();
        $from = $from_date->year . sprintf('%02d', $from_date->month);

        $to_date = Carbon::today()->addMonthsNoOverflow(2)->endOfMonth();
        $to = $to_date->year . sprintf('%02d', $to_date->month);
        return Hospital::with([
            'contract_information',
            'courses.course_details',
            'courses.course_details.major_classification',
            'courses.course_details.middle_classification',
            'courses.course_details.minor_classification',
            'courses.course_images.hospital_image',
            'options',
            'prefecture',
            'district_code',
            'medical_treatment_times',
            'hospital_categories.image_order',
            'hospital_categories.hospital_image'
        ])
            ->whereHas('courses' , function($q) {
                $q->where('courses.is_category', 0);
            })
            ->find($hospital_id);
    }

    /**
     * 検査コースコンテンツ取得
     *
     * @param  Collection $courses
     * @param  string $hospital_code ドクネットID
     * @return Illuminate\Support\Collection 整形結果
     */
    private function getContent($hospital_id)
    {
        $entity = Hospital::with([
            'contract_information',
            'hospital_details',
            'hospital_details.minor_classification',
            'hospital_details.minor_classification.major_classification',
            'hospital_details.minor_classification.middle_classification',
            'hospital_categories',
            'hospital_categories.image_order',
            'hospital_categories.hospital_image',
            'hospital_categories.interview_details',
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

    private function getReserveCnt($hospital_codes) {

        $hospital_code_array = explode(',', $hospital_codes);
        $from = Carbon::today()->subDay(30);
        $to = Carbon::today();

        $hospitals = Hospital::with([
            'contract_information',
            'courses' => function($q) {
                $q->where('publish_start_date', '<=', Carbon::today())
                    ->orWhereNull('publish_start_date');
                $q->where('publish_end_date', '>=', Carbon::today())
                    ->orWhereNull('publish_end_date');
                $q->where('is_category', 0);
                $q->where('web_reception', 0);
            },
            'courses.reservations' => function ($q) use ($from, $to) {
                $q->where('reservation_status', '<>', ReservationStatus::CANCELLED);
                $q->whereBetween('reservation_date', [$from, $to]);
            }
        ])
            ->where('hospitals.status', Status::VALID)
            ->whereHas('contract_information' , function($q) use($hospital_code_array) {
                $q->whereIn('contract_informations.code', $hospital_code_array);
            })
            ->get();
        $results = [];

        foreach ($hospitals as $hospital) {

            if (empty($hospital->contract_information)) {
                continue;
            }

            if (empty($hospital->courses)) {
                continue;
            }

            $result = [];
            foreach ($hospital->courses as $course) {
                if (empty($course->reservations)) {
                    $result[] = ['course_no' => $course->id, 'r_vol' => 0];
                    continue;
                } else {
                    $result[] = ['course_no' => $course->id, 'r_vol' => $course->reservations->count()];
                }
            }

            $results[] = [$hospital->contract_information->code => $result];
        }

        return $results;
    }
}
