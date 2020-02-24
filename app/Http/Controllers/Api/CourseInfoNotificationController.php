<?php

namespace App\Http\Controllers\Api;

use App\Course;
use App\CourseFutanCondition;
use App\CourseMatch;
use App\Hospital;
use App\KenshinSysCooperation;
use App\KenshinSysCourse;
use App\KenshinSysCourseWaku;
use App\KenshinSysDantaiInfo;
use App\KenshinSysOption;
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
        $app_name = env('APP_ENV');
        $ip = $request->ip();
        if ($app_name == 'production') {
            $app_kbn = '1';
        } else {
            $app_kbn = '2';
        }

        // パラメータチェック
        $Ocp_Apim_Subscription_key = $request->header('Ocp-Apim-Subscription-key');
        $partner_code = $request->header('X-Partner-Code');
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
                if (!empty($course['courseDeleteFlg']) && $course['courseDeleteFlg'] == '1') {
                    continue;
                }
                if (!empty($course['courseDeleteFlg']) && $course['courseDeleteFlg'] != '1') {
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
                if (empty($course['courseAgeKisanKbn']) || !is_numeric($course['courseAgeKisanKbn']) || intval($course['courseAgeKisanKbn']) < 0 || intval($course['courseAgeKisanKbn']) > 13) {
                    return $this->createResponse($messages['errorValidationId']);
                }
                if ((intval($course['courseAgeKisanKbn']) <= 13 && intval($course['courseAgeKisanKbn']) >= 8)
                    && (empty($course['courseAgeKisanDate']) || !is_numeric($course['courseAgeKisanDate']) || strlen($course['courseAgeKisanDate']) != 4)) {
                    return $this->createResponse($messages['errorValidationId']);
                }
                if (empty($course['courseFutanJoukenList'])) {
                    return $this->createResponse($messages['errorValidationId']);
                }
                foreach ($course['courseFutanJoukenList'] as $futanJouken) {
                    if (empty($futanJouken['joukenNo']) || !is_numeric($futanJouken['joukenNo']) || strlen($futanJouken['joukenNo']) > 10) {
                        return $this->createResponse($messages['errorValidationId']);
                    }
                    if (empty($futanJouken['sex']) || !is_numeric($futanJouken['sex']) || (intval($futanJouken['sex']) != 1 && intval($futanJouken['sex']) != 2 && intval($futanJouken['sex']) != 3)) {
                        return $this->createResponse($messages['errorValidationId']);
                    }
                    if (empty($futanJouken['honninKbn']) || !is_numeric($futanJouken['honninKbn']) || (intval($futanJouken['honninKbn']) != 1 && intval($futanJouken['honninKbn']) != 2 && intval($futanJouken['honninKbn']) != 3)) {
                        return $this->createResponse($messages['errorValidationId']);
                    }
                    if (!is_numeric($futanJouken['futanKin']) || strlen($futanJouken['futanKin']) > 9) {
                        return $this->createResponse($messages['errorValidationId']);
                    }
                }

                if (isset($course['optionList'])) {
                    foreach ($course['optionList'] as $option) {
                        if (empty($option['optionNo'])) {
                            continue;
                        }
                        if (empty($option['optionNo']) || !is_numeric($option['optionNo']) || strlen($option['optionNo']) > 10) {
                            return $this->createResponse($messages['errorValidationId']);
                        }
                        if (empty($option['optionNm'])) {
                            return $this->createResponse($messages['errorValidationId']);
                        }
                        if (empty($option['optionAgeKisanKbn']) || intval($option['optionAgeKisanKbn']) < 0 || intval($option['optionAgeKisanKbn']) > 13) {
                            return $this->createResponse($messages['errorValidationId']);
                        }
                        if (
                            (!empty($option['optionAgeKisanDate']) && (!is_numeric($option['optionAgeKisanDate']) || strlen($option['optionAgeKisanDate']) != 4))) {
                            return $this->createResponse($messages['errorValidationId']);
                        }
                        if (empty($option['optionFutanJoukenList'])) {
                            return $this->createResponse($messages['errorValidationId']);
                        }
                        foreach ($option['optionFutanJoukenList'] as $optionFutanJouken) {
                            if (empty($optionFutanJouken['sex']) || !is_numeric($optionFutanJouken['sex']) || (intval($optionFutanJouken['sex']) != 1 && intval($optionFutanJouken['sex']) != 2 && intval($optionFutanJouken['sex']) != 3)) {
                                return $this->createResponse($messages['errorValidationId']);
                            }
                            if (empty($optionFutanJouken['honninKbn']) || !is_numeric($optionFutanJouken['honninKbn']) || (intval($optionFutanJouken['honninKbn']) != 1 && intval($optionFutanJouken['honninKbn']) != 2 && intval($optionFutanJouken['honninKbn']) != 3)) {
                                return $this->createResponse($messages['errorValidationId']);
                            }
                            if (!is_numeric($optionFutanJouken['futanKin']) || strlen($optionFutanJouken['futanKin']) > 9) {
                                return $this->createResponse($messages['errorValidationId']);
                            }
                            if (empty($optionFutanJouken['yusenKbn']) || !is_numeric($optionFutanJouken['yusenKbn']) || strlen($optionFutanJouken['yusenKbn']) > 2) {
                                return $this->createResponse($messages['errorValidationId']);
                            }
                            if (empty($optionFutanJouken['riyouBgnDate']) || !is_numeric($optionFutanJouken['riyouBgnDate']) || strlen($optionFutanJouken['riyouBgnDate'])!= 8) {
                                return $this->createResponse($messages['errorValidationId']);
                            }
                            if (empty($optionFutanJouken['riyouEndDate']) || !is_numeric($optionFutanJouken['riyouEndDate']) || strlen($optionFutanJouken['riyouEndDate'])!= 8) {
                                return $this->createResponse($messages['errorValidationId']);
                            }
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

            DB::commit();
        } catch (\Throwable $e) {
            $message = '[健診システム連携コース通知API] DBの登録に失敗しました。';
//            Log::error($message, [
//                '健診システム連携情報' => $kenshin_sys_cooperation->toArray(),
//                'exception' => $e,
//            ]);
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
            'statusCode' => strval($statusCode),
            'resultCode' => $message['code'],
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

        $course_ids = [];
        foreach ($course_list as $kenshin_course) {
            $course_ids[] = $kenshin_course['courseNo'];
            $course = KenshinSysCourse::where('kenshin_sys_course_no', $kenshin_course['courseNo'])
                ->where('kenshin_sys_hospital_id', $params['hospital_id'])
                ->first();

            if ($kenshin_course['courseDeleteFlg'] == 1 && $course) {
                foreach ($course->course_futan_conditions as $course_futan_condition) {
                    TargetAge::where('course_futan_condition_id', $course_futan_condition->id)->forceDelete();
                    $course_futan_condition->forceDelete();
                }
                foreach ($course->kenshin_sys_options as $kenshin_option) {
                    foreach ($kenshin_option->option_futan_conditions as $option_futan_condition) {
                        OptionTargetAge::where('option_futan_condition_id', $option_futan_condition->id)->forceDelete();
                        $option_futan_condition->forceDelete();
                    }
                    $kenshin_option->forceDelete();
                }
                $course->forceDelete();
                continue;
            } elseif ($kenshin_course['courseDeleteFlg'] == 1 && !$course) {
                continue;
            }

            if (!$course) {
                $course = new KenshinSysCourse();
            }
            $course->kenshin_sys_hospital_id = $params['hospital_id'];
            $course->kenshin_sys_dantai_info_id = $kenshinSysDantaiInfo->id;
            $course->kenshin_sys_dantai_no = $params['dantai_no'];
            $course->kenshin_sys_course_no = $kenshin_course['courseNo'];
            $course->kenshin_sys_course_name = $kenshin_course['courseNm'];
            $course->kenshin_sys_course_kingaku = $kenshin_course['courseKin'];
            $course->kenshin_sys_riyou_bgn_date = Carbon::createFromFormat('Ymd', $kenshin_course['riyouBgnDate'])->format('Y-m-d');
            $course->kenshin_sys_riyou_end_date = Carbon::createFromFormat('Ymd', $kenshin_course['riyouEndDate'])->format('Y-m-d');
            $course->kenshin_sys_course_age_kisan_kbn = $kenshin_course['courseAgeKisanKbn'];
            $course->kenshin_sys_course_age_kisan_date = Carbon::parse($kenshin_course['courseAgeKisanDate']) ?? null;
            $course->save();

            // コース負担条件登録
            CourseFutanCondition::where('kenshin_sys_course_id', $course->id)->forceDelete();
            $kenshin_course_futan_jouken_list = $kenshin_course['courseFutanJoukenList'];
            if (!empty($kenshin_course_futan_jouken_list)) {
                foreach ($kenshin_course_futan_jouken_list as $kenshin_course_futan_jouken) {
                    $course_futan_condition = new CourseFutanCondition();
                    $course_futan_condition->kenshin_sys_course_id = $course->id;
                    $course_futan_condition->jouken_no = $kenshin_course_futan_jouken['joukenNo'];
                    $course_futan_condition->sex = $kenshin_course_futan_jouken['sex'];
                    $course_futan_condition->honnin_kbn = $kenshin_course_futan_jouken['honninKbn'];
                    $course_futan_condition->futan_kingaku = $kenshin_course_futan_jouken['futanKin'];
                    $course_futan_condition->save();

                    $kenshin_target_ages = $kenshin_course_futan_jouken['targetAgeList'];
                    if (!empty($kenshin_target_ages)) {
                        TargetAge::where('course_futan_condition_id', $course_futan_condition->id)->forceDelete();
                        foreach ($kenshin_target_ages as $kenshin_target_age) {
                            if (empty($kenshin_target_age) || !is_numeric($kenshin_target_age)) {
                                continue;
                            }
                            $target_age = new TargetAge();
                            $target_age->course_futan_condition_id = $course_futan_condition->id;
                            $target_age->target_age = $kenshin_target_age;
                            $target_age->save();
                        }
                    }
                }
            }

            //オプション登録
            if (!isset($kenshin_course['optionList'])) {
                continue;
            }
            $kenshin_option_list = $kenshin_course['optionList'];
            $option_nos = [];
            if (!empty($kenshin_option_list)) {
                foreach ($kenshin_option_list as $kenshin_option) {
                    if (empty($kenshin_option['optionNo'])) {
                        continue;
                    }
                    $option_nos[] = $kenshin_option['optionNo'];
                    $option = KenshinSysOption::where('kenshin_sys_course_id', $course->id)
                        ->where('kenshin_sys_option_no', $kenshin_option['optionNo'])->first();
                    if ($option) {
                        $option_futan_conditions = OptionFutanCondition::where('kenshin_sys_option_id', $option->id)->get();
                        foreach ($option_futan_conditions as $option_futan_condition) {
                            OptionTargetAge::where('option_futan_condition_id', $option_futan_condition->id)->forceDelete();
                            $option_futan_condition->forceDelete();
                        }

                    }
                    if ($kenshin_option['optionDeleteFlg'] == 1) {
                        // オプション削除
                        $option->forceDelete();
                        continue;
                    }

                    if (!$option) {
                        $option = new KenshinSysOption();
                    }

                    $option->kenshin_sys_course_id = $course->id;
                    $option->kenshin_sys_option_no = $kenshin_option['optionNo'];
                    $option->kenshin_sys_option_name = $kenshin_option['optionNm'];
                    $option->kenshin_sys_option_age_kisan_kbn = $kenshin_option['optionAgeKisanKbn'];
                    $option->kenshin_sys_option_age_kisan_date = Carbon::parse($kenshin_option['optionAgeKisanDate']) ?? null;
                    $option->save();

                    $option_futan_jouken_list = $kenshin_option['optionFutanJoukenList'];
                    if (!empty($option_futan_jouken_list)) {
                        foreach ($option_futan_jouken_list as $kenshin_option_futan_jouken) {
                            $option_futan_condition = new OptionFutanCondition();
                            $option_futan_condition->kenshin_sys_option_id = $option->id;
//                            $option_futan_condition->jouken_no = $kenshin_option_futan_jouken['joukenNo'];
                            $option_futan_condition->sex = $kenshin_option_futan_jouken['sex'];
                            $option_futan_condition->honnin_kbn = $kenshin_option_futan_jouken['honninKbn'];
                            $option_futan_condition->futan_kingaku = $kenshin_option_futan_jouken['futanKin'];
                            $option_futan_condition->yusen_kbn = $kenshin_option_futan_jouken['yusenKbn'];
                            $option_futan_condition->riyou_bgn_date = $kenshin_option_futan_jouken['riyouBgnDate'];
                            $option_futan_condition->riyou_end_date = $kenshin_option_futan_jouken['riyouEndDate'];
                            $option_futan_condition->save();

                            $kenshin_option_target_ages = $kenshin_option_futan_jouken['targetAgeList'];
                            if (!empty($kenshin_option_target_ages)) {
                                foreach ($kenshin_option_target_ages as $kenshin_option_target_age) {
                                    if (empty($kenshin_option_target_age) || !is_numeric($kenshin_option_target_age)) {
                                        continue;
                                    }
                                    $optiopn_target_age = new OptionTargetAge();
                                    $optiopn_target_age->option_futan_condition_id = $option_futan_condition->id;
                                    $optiopn_target_age->target_age = $kenshin_option_target_age;
                                    $optiopn_target_age->save();
                                }
                            }
                        }
                    }
                }

                // オプション削除
                $this->deleteOption($course->id, $option_nos);
            }
        }

        // コース情報削除
//        $this->deleteCourse($params['hospital_id'], $params['dantai_no'], $course_ids);
    }

    private function deleteOption($kenshin_sys_course_id, $option_nos) {
        $kenshin_sys_options = KenshinSysOption::whereNotIn('kenshin_sys_option_no', $option_nos)
            ->where('kenshin_sys_course_id', $kenshin_sys_course_id)
            ->get();

        if ($kenshin_sys_options) {
            foreach ($kenshin_sys_options as $kenshin_sys_option) {
                $option_futan_conditions = OptionFutanCondition::where('kenshin_sys_option_id', $kenshin_sys_option->id)->get();
                if ($option_futan_conditions) {
                    foreach ($option_futan_conditions as $option_futan_condition) {
                        OptionTargetAge::where('option_futan_condition_id', $option_futan_condition->id)->forceDelete();
                        $option_futan_condition->forceDelete();
                    }
                }
                $kenshin_sys_option->forceDelete();
            }
        }
    }

    /**
     * @param $hospital_id
     * @param $dantai_no
     * @param $course_ids
     */
    private function deleteCourse($hospital_id, $dantai_no, $course_ids) {

        $del_kenshin_sys_courses =  KenshinSysCourse::where('kenshin_sys_hospital_id', $hospital_id)
            ->where('kenshin_sys_dantai_no', $dantai_no)
            ->whereNotIn('kenshin_sys_course_no', $course_ids)
            ->select('id')
            ->get();

        if ($del_kenshin_sys_courses) {

            foreach ($del_kenshin_sys_courses as $kenshin_sys_course) {
                KenshinSysCourseWaku::where('kenshin_sys_course_id', $kenshin_sys_course->id)->forceDelete();

                $kenshin_sys_course_conditions = CourseFutanCondition::where('kenshin_sys_course_id', $kenshin_sys_course->id)->get();
                if ($kenshin_sys_course_conditions) {
                    foreach ($kenshin_sys_course_conditions as $kenshin_sys_course_condition) {
                        TargetAge::where('course_futan_condition_id', $kenshin_sys_course_condition->id)->forceDelete();
                        $kenshin_sys_course_condition->forceDelete();
                    }
                }

                $kenshin_sys_options = KenshinSysOption::where('kenshin_sys_course_id', $kenshin_sys_course->id)->get();

                if ($kenshin_sys_options) {
                    foreach ($kenshin_sys_options as $kenshin_sys_option) {
                        $option_futan_conditions = OptionFutanCondition::where('kenshin_sys_option_id', $kenshin_sys_option->id)->get();
                        if ($option_futan_conditions) {
                            foreach ($option_futan_conditions as $option_futan_condition) {
                                OptionTargetAge::where('option_futan_condition_id', $option_futan_condition->id)->forceDelete();
                                $option_futan_condition->forceDelete();
                            }
                        }
                        $kenshin_sys_option->forceDelete();
                    }
                }
                CourseMatch::where('kenshin_sys_course_id', $kenshin_sys_course->id)->forceDelete();
                $kenshin_sys_course->forceDelete();
            }
        }
    }
}
