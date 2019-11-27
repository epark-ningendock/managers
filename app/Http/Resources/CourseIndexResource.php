<?php

namespace App\Http\Resources;

use App\Station;

class CourseIndexResource extends CourseIndexBaseResource
{
    /**
     * 検査コース情報 resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $course = $this['course'];
//        $month_data = $this['monthly_data'];
//        $day_data = $this['daily_data'];
        $course_base_resource = new CourseIndexBaseResource($course);
        $rails = [
            $course->hospital->rail1,
            $course->hospital->rail2,
            $course->hospital->rail3,
            $course->hospital->rail4,
            $course->hospital->rail5
        ];

        $stations = [
            $course->hospital->station1,
            $course->hospital->station2,
            $course->hospital->station3,
            $course->hospital->station4,
            $course->hospital->station5
        ];
        $accesses = [
            $course->hospital->access1,
            $course->hospital->access2,
            $course->hospital->access3,
            $course->hospital->access4,
            $course->hospital->access5
        ];

        return collect([])
            ->put('status', 0)        
            ->put('no', $course->id)
            ->put('url_basic', $course->hospital->url)
            ->put('hospital_code', $course->hospital->contract_information->code)
            ->put('name', $course->hospital->name)
            ->put('pref_name', $course->hospital->prefecture->name)
            ->put('district_name', $course->hospital->district_code->name)
            ->put('address1', $course->hospital->address1)
            ->put('address2', $course->hospital->address2)
            ->put('tel_ppc',  $course->hospital->paycall)
            ->put('stations', Station::getStations($rails, $stations, $accesses))
            ->put('non_consiltation', $course->hospital->consultation_note)
            ->put('non_consultation_note', $course->hospital->memo)
            ->put('hospital_category', new HospitalCategoryResource($course->hospital))
            ->merge($course_base_resource->baseCollections())
            ->merge(new CourseContentBaseResource($course))
//            ->put('month_calender', collect($month_data)->map(function ($c) {
//                return (object)[
//                    'yyyymm' => $c[0],
//                    // 予約可否配列の積をとり、0になればどこかに「受付可能(0)」あり
//                    'apoint_ok' => $c[1],
//                     ];
//                })->toArray(),)
//            ->put('all_calender', collect($day_data)->map(function ($c) {
//                    return (object)[
//                        $c[0] => [
//                            'appoint_status' => $c[1],
//                            'appoint_num' => $c[2],
//                            'reservation_frames' => $c[3],
//                            'closed_day' => $c[4]
//                        ]
//                    ];
//                }))
            ->toArray();
    }

}
