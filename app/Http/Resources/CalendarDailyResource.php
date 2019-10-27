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
        $search_cond = $this['search_cond'];
        $course = $this['course'];
        $day_data = $this['day_data'];
        return [
            'status' => 0,
            'no' => $course->hospital_id,
            'hospital_code' => $search_cond->hospital_code,
            'course_no' => $course->id,
            'course_code' => $course->code,
            'all_calender' => collect($day_data)->map(function ($c) {
                return (object)[
                    $c[0] => [
                    'appoint_status' => $c[1],
                    'appoint_num' => $c[2],
                    'reservation_frames' => $c[3],
                    'closed_day' => $c[4]
                    ]
                ];
            }),
        ];
    }
}