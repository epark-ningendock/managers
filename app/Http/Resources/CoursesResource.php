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

        // calendar_days追加要素セット
        $_courses = parent::modifyCalendarDays($this);

        return collect([])
            ->merge(parent::baseCollections())
            ->put('course_img', ImagePathsResource::collection($this->getCourseImg($this->course_images)))
            ->put('course_point', $this->course_point)
            ->put('category', CourseDetailCategoriesResource::collection($this->course_details))
            ->put('recommended', $recommended)
            ->put('course_option_flag', isset($this->course_option) ? 1 : 0)
            ->put('month_calender', new MonthlyCalendarResource($this))
            ->put('all_calender', new DailyCalendarResource($this))
            ->toArray();
    }


}
