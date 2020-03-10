<?php

namespace App\Http\Resources;

class CoursesResource extends CoursesBaseResource
{
    /**
     * 医療機関検査コース一覧 into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // おすすめ
        $recommended = [];
        foreach ($this->course_details as $detail) {
            if ($detail->major_classification_id == 24 && !empty($detail->inputstring)) {
                array_push($recommended, $detail->inputstring);
            }
        }

        return collect([])
            ->merge(parent::baseCollections())
            ->put('course_img', ImagePathsResource::collection($this->getCourseImg($this->course_images)))
            ->put('course_point', $this->course_point)
            ->put('category', $this->getCategory())
            ->put('recommended', $recommended)
            ->put('course_option_flag', $this->hasCourseOption())
//            ->put('month_calender', new MonthlyCalendarResource($this))
//            ->put('all_calender', new DailyCalendarResource($this))
            ->toArray();
    }

    private function hasCourseOption() {

        foreach ($this->course_option as $op) {
            if (!empty($op) && !empty($op->id) {
                return 1;
            }
        }
        return 0;
    }

    private function getCategory() {

        $results = [];
        foreach ($this->course_details as $detail) {
            if ($detail->select_status == 1 && $detail->status == '1') {
                $result = [
                    'id' => $detail->minor_classification_id,
                    'title' => $detail->minor_classification->icon_name,
                    'text' => $detail->minor_classification->name];
                $results[] = $result;
            }
        }

        return $results;
    }

}
