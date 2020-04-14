<?php

namespace App\Http\Resources;

use App\Enums\Status;
use Illuminate\Http\Resources\Json\Resource;

class CourseContentsResource extends Resource
{
    /**
     * 検査コースコンテンツ情報 resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $course = $this['course'];
        $hospital = $this['hospital'];

        return collect([])
            ->put('status', 0)        
            ->put('no', $hospital->id)
            ->put('hospital_code', $hospital->contract_information->code)
            ->put('course_no', $course->id)
            ->put('course_code', $course->code)
            ->put('sho_name', $this->createShoname($course))
            ->merge(new CourseContentBaseResource($course))
            ->toArray();
    }

    /**
     * @return string
     *
     */
    private function createShoname($course) {
        $result = '';
        foreach ($course->course_details as $detail) {
            if ($detail->major_classification_id == 13
                && $detail->middle_classification_id == 30
                && $detail->select_status == 1
                && $detail->status == Status::VALID) {
                $result = $result . $detail->minor_classification->name . ',';
            }
        }

        if (!empty($result)) {
            return rtrim($result, ',');
        }

        return $result;
    }
}
