<?php

namespace App\Http\Resources;

use App\Services\KenshinRelationService;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Log;

class CalendarDailyResource extends Resource
{

    /**
     * 検査コース空満情報（日別）resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->kenshin_relation_flg && !empty($this->kenshin_sys_courses) && count($this->kenshin_sys_courses) > 0) {
            $results = [];
            try {
                $medicalExamSysId = $this->hospital->medical_examination_system_id;
                $serv = new KenshinRelationService();
                $headers = $serv->createKenshinHeder($medicalExamSysId);
                $uri = $serv->getApiPath($medicalExamSysId).'coursewaku';
                $params = $serv->createKenshinCourseWakuParam($this, null, null);
                $client = app()->make(Client::class);
                $response = $client->request('POST', $uri, [
                    'headers' => $headers,
                    'json' => $params,
                ]);

                $res = json_decode($response->getBody()->getContents(), true);

                $riyou_start_date = $this->kenshin_sys_courses[0]->kenshin_sys_riyou_bgn_date->format('Ymd');
                $riyou_end_date = $this->kenshin_sys_courses[0]->kenshin_sys_riyou_end_date->format('Ymd');

                if (!empty($res)) {
                    $wakus = $res['dayWakuList'];
                    foreach ($wakus as $waku) {
                        $waku_cnt = 0;
                        if ($riyou_start_date > $waku['day'] || $riyou_end_date < $waku['day']) {
                            $results[$waku['day']] = ['appoint_status' => 2,
                                'reservation_frames' => 0,
                                'appoint_num' => 0,
                                'closed_day' => 1];
                        } else {
                            foreach ($waku['wakuInfoList'] as $waku_info) {
                                Log::info('枠:' .  (int) $waku_info['akiWakuCount']);
                                $waku_cnt = $waku_cnt + (int) $waku_info['akiWakuCount'];
                            }

                            $results[$waku['day']] = ['appoint_status' => $waku_cnt > 0 ? 0 : 2,
                                'reservation_frames' => $waku_cnt,
                                'appoint_num' => 0,
                                'closed_day' => 0];
                        }
                    }
                }

            } catch (\Exception $e) {
                $from = Carbon::today();
                $to = Carbon::today()->addMonthsNoOverflow(5)->endOfMonth();
                $count = $from->diffInDays($to);
                for ($i = 0; $i < $count; $i++) {

                    $results[$from->format('Ymd')] = ['appoint_status' => 2,
                        'reservation_frames' => 0,
                        'appoint_num' => 0,
                        'closed_day' => 0];
                    $from->addDay();
                }
                $results[$from->format('Ymd')] = ['appoint_status' => 2,
                    'reservation_frames' => 0,
                    'appoint_num' => 0,
                    'closed_day' => 0];
            }

            return $results;

        } else {
            $reserv_enable_date = Carbon::today()->addMonth(floor($this->reception_start_date / 1000))->addDay($this->reception_start_date % 1000);
            $reserv_enableto_date = Carbon::today()->addMonth(floor($this->reception_end_date / 1000))->addDay($this->reception_end_date % 1000);

            $all_calendars = $this->calendar_days;

            $results = [];

            foreach ($all_calendars as $calendar_day) {
                $holiday_flg = 0;
                if ($calendar_day->is_holiday == 1) {
                    $holiday_flg = 1;
                }

                $appoint_status = 0;
                if ($calendar_day->date < $reserv_enable_date) {
                    $appoint_status = 1;
                }

                if ($calendar_day->date >= $reserv_enableto_date) {
                    $appoint_status = 2;
                }

                if ($calendar_day->reservation_frames <= $calendar_day->reservation_count) {
                    $appoint_status = 2;
                }

                $results[$calendar_day->date->format('Ymd')] = ['appoint_status' =>$appoint_status, 'reservation_frames' => $calendar_day->reservation_frames ?? 0, 'appoint_num' => $calendar_day->reservation_count ?? 0, 'closed_day' => $holiday_flg];

            }

            return $results;
        }
    }
}