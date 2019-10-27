<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class CalendarMonthlyResource extends Resource
{
    /**
     * 検査コース空満情報（月別） resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $search_cond = $this['search_cond'];
        $course = $this['course'];
        $month_data = $this['month_data'];

        return [
            'status' => 0,
            'no' => $course->hospital_id,
            'hospital_code' => $search_cond->hospital_code,
            'course_no' => $course->id,
            'course_code' => $course->code,
            'month_calender' => collect($month_data)->map(function ($c) {
                return (object)[
                    'yyyymm' => $c[0],
                    // 予約可否配列の積をとり、0になればどこかに「受付可能(0)」あり
                    'apoint_ok' => $c[1],
                ];
            })->toArray(),
        ];
    }
}
