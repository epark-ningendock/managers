<?php

namespace App\Services;


use App\Enums\Gender;
use App\Enums\GenderTak;
use App\KenshinSysCooperation;
use Carbon\Carbon;
use Log;


class KenshinRelationService {

    public function createKenshinHeder($medical_sys_id) {

        $kenshin_sys_cooperation = $this->getKenshinSysCooperation($medical_sys_id);

        $headers = [
            // コンテンツタイプ 固定値「application/json; charset=utf-8」
            'Content-Type' => 'application/json; charset=utf-8',
            'Ocp-Apim-Subscription-key' => $kenshin_sys_cooperation->subscription_key,
            'X-Partner-Code' => $kenshin_sys_cooperation->partner_code
        ];

        return $headers;
    }

    /**
     * @param $medical_sys_id
     * @return mixed
     */
    public function getKenshinSysCooperation($medical_sys_id) {
        $app_name = env('APP_ENV');
        if ($app_name == 'production') {
            $app_kbn = '1';
        } else {
            $app_kbn = '2';
        }
        return KenshinSysCooperation::where('medical_examination_system_id', $medical_sys_id)
            ->where('app_kbn', $app_kbn)
            ->first();
    }

    /**
     * @param $medical_sys_id
     * @return \Illuminate\Config\Repository|mixed
     */
    public function getApiPath($medical_sys_id) {
        $app_name = env('APP_ENV');

        if ($medical_sys_id == config('constant.medical_exam_sys_id.tak')) {
            if ($app_name == 'production') {
                return config('constant.tak_api.prod.api_url');
            } else {
                return config('constant.tak_api.stg.api_url');
            }
        } elseif($medical_sys_id == config('constant.medical_exam_sys_id.itec')) {
            if ($app_name == 'production') {
                return config('constant.itec_api.prod.api_url');
            } else {
                return config('constant.itec_api.stg.api_url');
            }
        }
    }

    /**
     * @param $course
     * @param $from_date
     * @param $to_date
     * @return array
     */
    public function createKenshinCourseWakuParam($course, $from_date, $to_date) {

        if (empty($from_date)) {
            $from_date = Carbon::today()->format('Ymd');
        }
        if (empty($to_date)) {
            $to_date = Carbon::today()->addMonthsNoOverflow(5)->endOfMonth()->format('Ymd');
        }

        return [
            'hospitalId' => $course->kenshin_sys_courses[0]->kenshin_sys_hospital_id,
            'dantaiNo' => $course->kenshin_sys_courses[0]->kenshin_sys_dantai_no,
            'courseNo' => $course->kenshin_sys_courses[0]->kenshin_sys_course_no,
            'sex' => $course->sex,
            'birth' => str_replace('-', '', $course->birth),
            'honninKbn' => $course->honnin_kbn,
            'targetBgnDate' => $from_date,
            'targetEndDate' => $to_date
        ];
    }
}
