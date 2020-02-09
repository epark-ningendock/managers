<?php

namespace App\Http\Resources;

use App\Station;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;

class CourseBasicResource extends Resource
{
    /**
     * 検査コース基本情報 resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $hospital = $this['hospital'];
        $course = $this['course'];

        return collect([])
            ->merge(new HospitalBasicResource($hospital))
            ->put('non_consultation', $hospital->consultation_note ?? '')
            ->put('non_consultation_note', $hospital->memo ?? '')
            ->put('public_status', $hospital->status)
            ->put('update_at', Carbon::parse($hospital->updated_at)->format('Y-m-d'))
            ->put('hospital_category', new HospitalCategoryResource($hospital))
            ->merge(new CourseBaseResource($course))
            ->toArray();
    }

}
