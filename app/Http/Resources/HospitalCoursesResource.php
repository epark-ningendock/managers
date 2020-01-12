<?php

namespace App\Http\Resources;

use App\Station;
use Illuminate\Http\Resources\Json\Resource;

class HospitalCoursesResource extends Resource
{
    /**
     * 医療機関検査コース一覧 リソースクラス
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return
            collect([])
                ->merge(new HospitalBasicResource($this))
                ->put('exam_type', $this->getCategoryType())
                ->put('courses', CoursesBaseResource::collection($this->courses))
                ->toArray();
    }

    /**
     * @return array
     */
    private function getCategoryType() {

        $results = [];
        $sort_key = [];
        $courses = $this->courses;

        foreach ($courses as $course) {
            foreach ($course->course_details as $detail) {
                if ($detail->major_classification_id == 13
                    && $detail->select_status == 1
                    && $detail->status == '1'
                ) {
                    $result = ['id' => $detail->minor_classification_id, 'title' => $detail->minor_classification->name];
                    $results[] = $result;
                    $sort_key[] = $detail->minor_classification_id;
                }
            }
        }

        $sort_key = array_unique($sort_key);
        $results = array_unique($results, SORT_REGULAR);
        array_multisort($sort_key, SORT_NATURAL, $results);
        return $results;
    }
}
