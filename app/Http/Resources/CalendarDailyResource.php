<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

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
        return [
            'status' => 0,
            'no' => $this->hospital->id,
            'hospital_code' => $this->hospital->contract_information->code,
            'course_no' => $this->id,
            'course_code' => $this->code,
            'all_calender' => $this->calendar_days->map(function ($c) {
                return (object)[
                    date('Ymd', strtotime($c->date)) => date('Ymd', strtotime($c->date)),
                    'appoint_status' => $c->appoint_status,
                    'appoint_num' => $c->appoint_num,
                    'reservation_frames' => $c->reservation_frames,
                    'closed_day' => $c->closed_day === 0 ? 0 : 1,
                ];
            }),
        ];
    }
}