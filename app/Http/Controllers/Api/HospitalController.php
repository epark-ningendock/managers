<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Hospital;

use App\Http\Requests\HospitalRequest;

use App\Http\Resources\HospitalIndexResource;
use App\Http\Resources\HospitalBasicResource;
use App\Http\Resources\HospitalCoursesResource;
use App\Http\Resources\HospitalContentsResource;

use Log;

class HospitalController extends Controller
{
    /**
     * 医療機関情報取得API
     *
     * @param  App\Http\Requests\HospitalRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function index(HospitalRequest $request)
    {
        return new HospitalIndexResource($this->getHospitalData($request->input('hospital_code')));
    }

    /**
     * 医療機関基本情報取得API
     *
     * @param  App\Http\Requests\HospitalRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function basic(HospitalRequest $request)
    {
        return new HospitalBasicResource($this->getHospitalData($request->input('hospital_code')));
    }

    /**
     * 医療機関検査コース一覧情報取得API
     *
     * @param  App\Http\Requests\HospitalRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function courses(HospitalRequest $request)
    {
        return new HospitalCoursesResource($this->getHospitalData($request->input('hospital_code')));
    }

    /**
     * 医療機関コンテンツ情報取得API
     *
     * @param  App\Http\Requests\HospitalRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function contents(HospitalRequest $request)
    {
        return new HospitalContentsResource($this->getContent($request->input('hospital_code')));
    }

    /**
     * 医療機関情報取得
     *
     * @param	object	$objDb			データベースオブジェクト
     * @param	string	$sHospitalNo	医療施設番号
     * @return	object					TOPページ情報
     **/
    public static function getHospitalData($hospital_code)
    {
        return Hospital::with(
            'contract_information',
            'courses.course_details',
            'courses.course_details.major_classification',
            'courses.course_details.middle_classification',
            'courses.course_details.minor_classification',
            'courses.course_images.hospital_image',
            'courses.calendar_days',
            'options',
            'prefecture',
            'district_code',
            'medical_treatment_times',
            'hospital_categories.image_order',
            'hospital_categories.hospital_image'
        )
            ->wherehas('contract_information', function ($q) use ($hospital_code) {
                $q->where('code', $hospital_code);
            })
            ->first();
    }

    /**
     * 検査コースコンテンツ取得
     * 
     * @param  Collection $courses
     * @param  string $hospital_code ドクネットID
     * @return Illuminate\Support\Collection 整形結果
     */
    private function getContent($hospital_code)
    {
        $entity = Hospital::with([
            'contract_information',
            'hospital_details',
            'hospital_details.hospital_minor_classification',
            'hospital_details.hospital_minor_classification.hospital_major_classification',
            'hospital_details.hospital_minor_classification.hospital_middle_classification',
            'hospital_categories',
            'hospital_categories.image_order',
            'hospital_categories.hospital_image',
            'hospital_categories.interview_details',
        ])
            ->wherehas('contract_information', function ($q) use ($hospital_code) {
                $q->where('code', $hospital_code);
            })
            ->first();

        return $entity;
    }
}
