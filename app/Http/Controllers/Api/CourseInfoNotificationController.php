<?php

namespace App\Http\Controllers\Api;

use App\Course;
use App\CourseFutanCondition;
use App\Hospital;
use App\KenshinSysCooperation;
use App\KenshinSysCourse;
use App\KenshinSysDantaiInfo;
use App\Option;
use App\OptionFutanCondition;
use App\OptionTargetAge;
use App\TargetAge;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;


class CourseInfoNotificationController extends Controller
{
    /**
     * コース情報を登録する
     * @param Request $request
     */
    public function registcourse(Request $request)
    {
        $messages = config('api.course_info_notification_api.message');
        $sysErrorMessages = config('api.unexpected_error.message');
        $app_name = env('APP_NAME');
        $ip = Request::ip();
        if ($app_name == 'production') {
            $app_kbn = '1';
        } else {
            $app_kbn = '2';
        }

        // パラメータチェック
        $Ocp_Apim_Subscription_key = $request->input('Ocp-Apim-Subscription-key');
        $partner_code = $request->input('X-Partner-Code');
        if (!isset($Ocp_Apim_Subscription_key)) {
            return $this->createResponse($messages['errorSubscriptionKeyId']);
        }
        if (!isset($partner_code)) {
            return $this->createResponse($messages['errorPartnerCdId']);
        }

        $kenshin_sys_cooperation = KenshinSysCooperation::where('ip', $ip)->first();
        if (!$kenshin_sys_cooperation) {
            return $this->createResponse($messages['errorAccessIp']);
        }

        if ($kenshin_sys_cooperation->app_kbn != $app_kbn) {
            return $this->createResponse($messages['errorAccessIp']);
        }

        if ($kenshin_sys_cooperation->partner_code != $partner_code) {
            return $this->createResponse($messages['errorPartnerCdId']);
        }

        if ($kenshin_sys_cooperation->subscription_key != $Ocp_Apim_Subscription_key) {
            return $this->createResponse($messages['errorSubscriptionKeyId']);
        }

        if (empty($request->input('dantaiNo'))
            || !is_numeric($request->input('dantaiNo'))
        || strlen($request->input('dantaiNo')) > 10) {
            return $this->createResponse($messages['errorValidationId']);
        }

        if (empty($request->input('dantaiNm'))) {
            return $this->createResponse($messages['errorValidationId']);
        }

        if (!empty($request->input('courseList'))) {
            foreach ($request->input('courseList') as $course) {
                if (empty($course['courseNo']) || !is_numeric($course['courseNo']) || strlen($course['courseNo']) > 10) {
                    return $this->createResponse($messages['errorValidationId']);
                }
                if (empty($course['courseNm'])) {
                    return $this->createResponse($messages['errorValidationId']);
                }
                if (empty($course['courseKin']) || !is_numeric($course['courseKin'])) {
                    return $this->createResponse($messages['errorValidationId']);
                }
                if (empty($course['riyouBgnDate']) || !is_numeric($course['riyouBgnDate']) || strlen($course['riyouBgnDate']) != 8) {
                    return $this->createResponse($messages['errorValidationId']);
                }
                if (empty($course['riyouEndDate']) || !is_numeric($course['riyouEndDate']) || strlen($course['riyouEndDate']) != 8) {
                    return $this->createResponse($messages['errorValidationId']);
                }
                if (empty($course['courseFutanJoukenList'])) {
                    return $this->createResponse($messages['errorValidationId']);
                }
                if (isset($course['optionList'])) {
                    foreach ($course['optionList'] as $option) {
                        if (empty($option['optionNo']) || !is_numeric($option['optionNo']) || strlen($option['optionNo']) > 10) {
                            return $this->createResponse($messages['errorValidationId']);
                        }
                        if (empty($option['optionNm'])) {
                            return $this->createResponse($messages['errorValidationId']);
                        }
                        if (empty($option['optoinAgeKisanKbn']) || !in_array($option['optoinAgeKisanKbn'], [1,2,3,4,5,6,7,8])) {
                            return $this->createResponse($messages['errorValidationId']);
                        }
                        if (empty($option['optionAgeKisanDate']) || !is_numeric($option['optionAgeKisanDate']) || strlen($option['optionAgeKisanDate']) != 8) {
                            return $this->createResponse($messages['errorValidationId']);
                        }
                        if (empty($course['optionFutanJoukenList'])) {
                            return $this->createResponse($messages['errorValidationId']);
                        }
                    }
                }
            }
        }

        $params = [
            'hospital_id' => $request->input('hospitalId'),
            'dantai_no' => $request->input('dantaiNo'),
            'dantai_nm' => $request->input('dantaiNm'),
            'course_list' => $request->input('courseList')
        ];

        DB::beginTransaction();
        try {
            // 登録
            $this->registCourseInfo($params);

            // 削除

            DB::commit();
        } catch (\Throwable $e) {
            $message = '[健診システム連携コース通知API] DBの登録に失敗しました。';
            Log::error($message, [
                '健診システム連携情報' => $kenshin_sys_cooperation->toArray(),
                'exception' => $e,
            ]);
            DB::rollback();
            return $this->createResponse($sysErrorMessages['errorDB']);
        }

        return $this->createResponse($messages['success']);
    }

    /**
     * レスポンスを生成する
     *
     * @param array $message
     * @return response
     */
    protected function createResponse( array $message, $statusCode = 200) {
        return response([
            'status_code' => strval($statusCode),
            'result_code' => $message['code'],
            'message' => $message['description'],
        ], $statusCode)
            ->header('Content-Type', 'application/json; charset=utf-8')
            ->header('date', new Carbon());
    }

    /**
     * コース情報登録
     * @param array $params
     */
    protected function registCourseInfo(array $params) {

        $kenshinSysDantaiInfo = KenshinSysDantaiInfo::where('kenshin_sys_hospital_id', $params['hospital_id'])
            ->where('kenshin_sys_dantai_no', $params['dantai_no'])
            ->first();

        if (! $kenshinSysDantaiInfo) {
            $kenshinSysDantaiInfo = new KenshinSysDantaiInfo();
        }
        $kenshinSysDantaiInfo->kenshin_sys_hospital_id = $params['hospital_id'];
        $kenshinSysDantaiInfo->kenshin_sys_dantai_no = $params['dantai_no'];
        $kenshinSysDantaiInfo->kenshin_sys_dantai_nm = $params['dantai_nm'];
        $kenshinSysDantaiInfo->save();

        // コース情報登録
        $course_list = $params['course_list'];
        if (!isset($course_list)) {
            return;
        }

        foreach ($course_list as $kenshin_course) {
            $course = KenshinSysCourse::where('kenshin_sys_course_no', $kenshin_course['courseNo'])
                ->where('kenshin_sys_hospital_id', $params['hospital_id'])
                ->first();
            if (!$course) {
                $course = new Course();
            }
            $course->hospital_id = $hospital->id;
            $course->kenshin_sys_dantai_info_id = $kenshinSysDantaiInfo->id;
            $course->kenshin_sys_course_no = $kenshin_course['courseNo'];
            $course->kenshin_sys_course_name = $kenshin_course['courseNm'];
            $course->kenshin_sys_course_kingaku = $kenshin_course['courseKin'];
            $course->kenshin_sys_riyou_bgn_date = $kenshin_course['riyouBgnDate'];
            $course->kenshin_sys_riyou_end_date = $kenshin_course['riyouEndDate'];
            $course->kenshin_sys_course_age_kisan_kbn = $kenshin_course['courseAgeKisanKbn'];
            $course->kenshin_sys_course_age_kisan_date = $kenshin_course['courseAgeKisanDate'];
            $course->save();

            // コース負担条件登録
            $kenshin_course_futan_jouken_list = $kenshin_course['courseFutanJoukenList'];
            if (!empty($kenshin_course_futan_jouken_list)) {
                foreach ($kenshin_course_futan_jouken_list as $kenshin_course_futan_jouken) {
                    $course_futan_condition = CourseFutanCondition::where('kenshin_sys_course_no', $kenshin_course['courseNo'])
                        ->where('jouken_no', $kenshin_course_futan_jouken['joukenNo'])
                        ->where('kenshin_sys_hospital_id', $params['hospital_id'])
                        ->first();
                    if (!$course_futan_condition) {
                        $course_futan_condition = new CourseFutanCondition();
                    }
                    $course_futan_condition->course_id = $course->id;
                    $course_futan_condition->kenshin_sys_course_no = $kenshin_course['courseNo'];
                    $course_futan_condition->jouken_no = $kenshin_course_futan_jouken['joukenNo'];
                    $course_futan_condition->sex = $kenshin_course_futan_jouken['sex'];
                    $course_futan_condition->honnin_kbn = $kenshin_course_futan_jouken['honninKbn'];
                    $course_futan_condition->futan_kingaku = $kenshin_course_futan_jouken['futanKin'];
                    $course_futan_condition->save();

                    $kenshin_target_ages = $kenshin_course_futan_jouken['targetAgeList'];
                    if (!empty($kenshin_target_ages)) {
                        TargetAge::where('course_futan_condition_id', $course_futan_condition->id)->delete();
                        foreach ($kenshin_target_ages as $kenshin_target_age) {
                            $target_age = new TargetAge();
                            $target_age->course_futan_condition_id = $course_futan_condition->id;
                            $target_age->target_age = $kenshin_target_age;
                            $target_age->save();
                        }
                    }
                }
            }

            //オプション登録
            $kenshin_option_list = $kenshin_course['optionList'];
            if (!empty($kenshin_option_list)) {
                foreach ($kenshin_option_list as $kenshin_option) {
                    $option = Option::where('hospital_id', $hospital->id)
                        ->where('kenshin_sys_course_no', $kenshin_course['courseNo'])
                        ->where('kenshin_sys_option_no', $kenshin_option['optionNo'])
                        ->first();

                    if (!$option) {
                        $option = new Option();
                        $option->name = $kenshin_option['optionNm'];
                    }
                    $option->kenshin_sys_course_no = $kenshin_course['courseNo'];
                    $option->kenshin_sys_option_no = $kenshin_option['optionNo'];
                    $option->kenshin_sys_option_nm = $kenshin_option['optionNm'];
                    $option->kenshin_sys_option_age_kisan_kbn = $kenshin_option['optoinAgeKisanKbn'];
                    $option->kenshin_sys_option_age_kisan_date = $kenshin_option['optionAgeKisanDate'];
                    $option->kenshin_sys_flg = 1;
                    $option->save();

                    $option_futan_jouken_list = $kenshin_option['optionFutanJoukenList'];
                    if (!empty($option_futan_jouken_list)) {
                        foreach ($option_futan_jouken_list as $kenshin_option_futan_jouken) {
                            $option_futan_condition = OptionFutanCondition::where('option_id', $option->id)
                                ->where('kenshin_sys_option_no', $kenshin_option['optionNo'])
                                ->where('jouken_no', $kenshin_option_futan_jouken['joukenNo'])
                                ->first();
                            if (!$option_futan_condition) {
                                $option_futan_condition = new OptionFutanCondition();
                                $option_futan_condition->option_id = $option->id;
                                $option_futan_condition->kenshin_sys_option_no = $kenshin_option['optionNo'];
                                $option_futan_condition->jouken_no = $kenshin_option_futan_jouken['joukenNo'];
                            }
                            $option_futan_condition->sex = $kenshin_option_futan_jouken['sex'];
                            $option_futan_condition->honnin_kbn = $kenshin_option_futan_jouken['honninKbn'];
                            $option_futan_condition->futan_kingaku = $kenshin_option_futan_jouken['futanKin'];
                            $option_futan_condition->yusen_kbn = $kenshin_option_futan_jouken['yusenKbn'];
                            $option_futan_condition->riyou_bgn_date = $kenshin_option_futan_jouken['riyouBgnDate'];
                            $option_futan_condition->riyou_end_date = $kenshin_option_futan_jouken['riyouEndDate'];
                            $option_futan_condition->save();

                            $kenshin_option_target_ages = $kenshin_option_futan_jouken['targetAgeList'];
                            if (!empty($kenshin_option_target_ages)) {
                                OptionTargetAge::where('option_futan_condition_id', $option_futan_condition->id)->delete();
                                foreach ($kenshin_option_target_ages as $kenshin_option_target_age) {
                                    $optiopn_target_age = new OptionTargetAge();
                                    $optiopn_target_age->option_futan_condition_id = $option_futan_condition->id;
                                    $optiopn_target_age->target_age = $kenshin_option_target_age;
                                    $optiopn_target_age->save();
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * @param $params
     */
    private function deleteCourse($params) {

    }
}
