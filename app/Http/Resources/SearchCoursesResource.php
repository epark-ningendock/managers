<?php

namespace App\Http\Resources;

class SearchCoursesResource extends CoursesBaseResource
{
    /**
     * 検査コース一覧検索 リソースクラス
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // おすすめ
        $recommended = $this->course_details->map(function ($d) {
            return $d->inputstring ?? '';
        });

        // calendar_days追加要素セット
//        $_courses = parent::modifyCalendarDays($this);

        return $this->baseCollections()
            ->put('hospital_code', $this->hospital->contract_information->code ?? '')
            ->put('course_img', ImagePathsResource::collection($this->getCourseImg($this->course_images)))
            ->put('course_point', parent::wrapWord($this->course_point))
            ->put('category', CourseDetailCategoriesResource::collection($this->course_details))
            ->put('recommended', $recommended)
            ->put('course_option_flag', isset($this->course_option) ? 1 : 0)
            ->put('month_calender', new MonthlyCalendarResource($_courses))
            ->put('paycall', $this->hospital->paycall ?? '')
            ->toArray();
    }
}
