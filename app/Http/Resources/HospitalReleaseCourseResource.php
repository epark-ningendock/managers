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
        return collect([])
            ->put('status', 0)
            ->merge($this->getReleaseCourses())
            ->toArray();
    }

    /**
     * 公開中コース情報取得
     */
    private function getReleaseCourses() {

        $hospitals = Hospital::with([
            'contract_information',
            'courses'
        ])
            ->whereHas('courses', function ($q) {
                $q->where('publish_start_date', '<=', Carbon::today())
                    ->orWhereNull('publish_start_date');
                $q->where('publish_end_date', '>=', Carbon::today())
                    ->orWhereNull('publish_end_date');
                $q->where('status', Status::VALID);
                $q->where('is_category', 0);
            })
            ->where('status', Status::VALID)
            ->get();

        $results = [];
        foreach ($hospitals as $hospital) {
            if (!isset($hospital->contract_information)) {
                continue;
            }
            $course_data = [];
            foreach ($hospital->courses as $course) {
                $course_data[] = ['course_code' => $course->code, 'course_no' => $course->id];
            }
            $results[] = ['hospital_code' => $hospital->contract_information->code, 'courses' => $course_data];
        }

        return $results;
    }
}
