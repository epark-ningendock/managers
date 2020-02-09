<?php

namespace App\Http\Resources;

use App\Enums\CalendarDisplay;
use App\Enums\Status;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;

use App\Enums\WebReception;

use Log;

class CalendarBaseResource extends Resource
{
    /**
     * カレンダー基本情報 resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->baseCollections()->toArray();
    }

    /**
     * 検査コース基本情報
     *
     * @return Illuminate\Support\Collection
     */
    protected function baseCollections()
    {
        $hospital_id = $this['hospital_id'];
        $hospital_code = $this['hospital_code'];
        $course = $this['course'];
        return collect(
            [
                'status' => 0,
                'no' => $hospital_id,
                'hospital_code' => $hospital_code,
                'course_no' => $course->id,
                'course_code' => $course->code,
                'all_calendar' => new CalendarDailyResource($course)
            ]
        );
    }
}
