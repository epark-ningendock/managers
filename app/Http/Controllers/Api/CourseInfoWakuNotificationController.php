<?php

namespace App\Http\Controllers\Api;

use App\Course;
use App\CourseFutanCondition;
use App\Enums\AppKbn;
use App\Hospital;
use App\KenshinSysCooperation;
use App\KenshinSysCourse;
use App\KenshinSysCourseWaku;
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
use mysql_xdevapi\Exception;
use Symfony\Component\Debug\Debug;


class CourseInfoWakuNotificationController extends Controller
{
    /**
     * コース枠情報を登録する
     * @param Request $request
     */
    public function registcoursewaku(Request $request)
    {
        $messages = config('api.course_info_notification_api.message');
        $sysErrorMessages = config('api.unexpected_error.message');
        $app_name = env('APP_ENV');
        $ip = $request->ip();
        if ($app_name == 'production') {
            $app_kbn = AppKbn::PRODUCTION;
        } else {
            $app_kbn = AppKbn::OTHER;
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

        $hospital = Hospital::where('kenshin_sys_hospital_id', $request->input('hospitalId'))->first();
        if (!$hospital) {
            return $this->createResponse($messages['errorValidationId']);
        }

        if (empty($request->input('dantaiNo'))
            || !is_numeric($request->input('dantaiNo'))
        || strlen($request->input('dantaiNo')) > 15) {
            return $this->createResponse($messages['errorValidationId']);
        }

        if (!empty($request->input('courseList'))) {
            foreach ($request->input('courseList') as $course) {
                if (empty($course['courseNo']) || !is_numeric($course['courseNo']) || strlen($course['courseNo']) > 15) {
                    return $this->createResponse($messages['errorValidationId']);
                }
                if (empty($course['joukenNo']) || !is_numeric($course['joukenNo']) || strlen($course['joukenNo']) > 10) {
                    return $this->createResponse($messages['errorValidationId']);
                }
            }
        }

        $params = [
            'hospital_id' => $request->input('hospitalId'),
            'dantai_no' => $request->input('dantaiNo'),
            'course_list' => $request->input('courseList')
        ];

        DB::beginTransaction();
        try {
            // 登録
            $this->registCourseWakuInfo($params);
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
            'statusCode' => strval($statusCode),
            'resultCode' => $message['code'],
            'message' => $message['description'],
        ], $statusCode)
            ->header('Content-Type', 'application/json; charset=utf-8')
            ->header('date', new Carbon());
    }

    /**
     * コース枠情報登録
     * @param array $params
     */
    protected function registCourseWakuInfo(array $params) {

        foreach ($params['course_list'] as $c) {
            // コース情報取得
            $course = KenshinSysCourse::where('kenshin_sys_hospital_id', $params['hospital_id'])
                ->where('kenshin_sys_dantai_no', $params['dantai_no'])
                ->where('kenshin_sys_course_no', $c['courseNo'])
                ->first();

            if (!$course) {
                continue;
            }

            foreach ($c['monthWakuList'] as $month_waku) {
                $kenshin_sys_course_waku = KenshinSysCourseWaku::where('kenshin_sys_course_id', $course->id)
                    ->where('year_month', $month_waku['month'])
                    ->first();

                if (!$kenshin_sys_course_waku) {
                    $kenshin_sys_course_waku = new KenshinSysCourseWaku();
                }
                $kenshin_sys_course_waku->kenshin_sys_course_id = $course->id;
                $kenshin_sys_course_waku->kenshin_sys_course_no = $c['courseNo'];
                $kenshin_sys_course_waku->jouken_no = $c['joukenNo'];
                $kenshin_sys_course_waku->year_month = $month_waku['month'];
                $kenshin_sys_course_waku->waku_kbn = $month_waku['wakuInfo'];
                $kenshin_sys_course_waku->save();
            }

        }
    }
}
