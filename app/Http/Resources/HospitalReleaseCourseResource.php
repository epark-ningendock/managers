<?php

namespace App\Http\Resources;

use App\Course;
use App\Enums\Status;
use App\Hospital;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;

class HospitalReleaseCourseResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'status' => 0,
            'hospital_code' => $this['hospital_code'],
            'courses' => CoursesReleaseResource::collection($this->getReleaseCourses())
        ];
    }

    /**
     * 公開中コース情報取得
     */
    private function getReleaseCourses() {

        $hospital = Hospital::join('contract_informations', 'contract_informations.hospital_id', 'hospitals.id')
            ->where('contract_informations.code', $this['hospital_code'])
            ->first();

        return Course::where('hospital_id', $hospital->id)
            ->where(function ($query) {
                $query->where('publish_start_date', '<=', Carbon::today())
                    ->orWhereNull('publish_start_date');
            })
            ->where(function ($query) {
                $query->where('publish_end_date', '>=', Carbon::today())
                    ->orWhereNull('publish_end_date');
            })
            ->get();
    }
}
