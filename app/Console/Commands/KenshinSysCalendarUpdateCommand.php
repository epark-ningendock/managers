<?php

namespace App\Console\Commands;

use App\CourseFutanCondition;
use App\KenshinSysCooperation;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use App\Jobs\PvAggregateJob;

class KenshinSysCalendarUpdateCommand extends Command
{
    protected $signature = 'kenshin-sys-course-waku-update';

    protected $description = '健診システム連携コース枠情報を更新する。';

    /**
     * 健診システム連携コース枠情報を更新するジョブを登録する
     *
     * @return バッチ実行成否
     */
    public function handle()
    {
        $messages = config('api.course_info_notification_api.message');
        $sysErrorMessages = config('api.unexpected_error.message');
        $app_name = env('APP_ENV');
        if ($app_name == 'production') {
            $app_kbn = '1';
        } else {
            $app_kbn = '2';
        }

        Log::info('[健診システムコース枠情報更新] 処理開始');

        // 健診連携医療機関情報取得
        $kenshin_sys_cooperations = KenshinSysCooperation::where('app_kbn', $app_kbn)->get();

        foreach ($kenshin_sys_cooperations as $kenshin_sys_cooperation) {
            foreach ($kenshin_sys_cooperation->hospitals as $hospital) {
                foreach ($hospital->kenshin_sys_courses as $kenshin_sys_course) {

                    $uri = $kenshin_sys_cooperation->api_url;
                    $headers = $this->getRequestHeaders($kenshin_sys_cooperation->subscription_key, $kenshin_sys_cooperation->partner_code);
                    $params = $this->getApiParams($kenshin_sys_course);
                    $client = app()->make(Client::class);

                }
            }
        }
    }

    /**
     * APIのリクエストヘッダーを作成する
     *
     * @return array
     */
    protected function getRequestHeaders($subscription_key, $partner_code)
    {
        $headers = [
            // サブスクリプションキー
            'Ocp-Apim-Subscription-key' => $subscription_key,
            'X-Partner-Code' => $partner_code,
            // コンテンツタイプ 固定値「application/json; charset=utf-8」
            'Content-Type' => 'application/json; charset=utf-8',
        ];

        return $headers;
    }

    /**
     * APIのリクエストパラメータを作成する
     *
     * @return array
     */
    private function getApiParams($kenshin_sys_course)
    {
        $params = [
            // 医療機関ID
            'hospitalId' => $kenshin_sys_course->kenshin_sys_hospital_id,
            // 団体番号
            'dantaiNo' => $kenshin_sys_course->kenshin_sys_dantai_no,
            // コース番号
            'courseNo' => $kenshin_sys_course->kenshin_sys_course_no,
            // 性別
            'sex' => '3',
            // 生年月日(yyyyMMdd)
            'birth' => '1',
            // 本人区分(本人)
            'honninKbn' => $cAccountNumber,

        ];

        return $params;
    }

    /**
     * @param $kenshin_sys_course
     */
    private function getSex($kenshin_sys_course) {

        $joukens = $kenshin_sys_course->course_futan_conditions;

        if (!isset($joukens)) {
            return CourseFutanCondition::tak_gendars()->value('3');
        }



    }
}
