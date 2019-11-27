<?php

namespace App\Http\Resources;

use App\CalendarDay;
use Carbon\Carbon;

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
        $recommended = [];
        foreach ($this->course_details as $detail) {
            if ($detail->major_classification_id == 24 && !empty($detail->inputstring)) {
                array_push($recommended, $detail->inputstring);
            }
        }

        // calendar_days追加要素セット
//        $_courses = parent::modifyCalendarDays($this);

        return $this->baseCollections()
            ->put('hospital_code', $this->hospital->contract_information->code ?? '')
            ->put('course_img', ImagePathsResource::collection($this->getCourseImg($this->course_images)))
            ->put('course_point', parent::wrapWord($this->course_point))
            ->put('category', $this->getCategory())
            ->put('recommended', $recommended)
            ->put('course_option_flag', isset($this->course_option) ? 1 : 0)
            ->put('month_calender', new MonthlyCalendarResource($this))
            ->put('paycall', $this->hospital->paycall ?? '')
            ->toArray();
    }

    private function getCategory() {

        $results = [];
        foreach ($this->course_details as $detail) {
            if ($detail->select_status == 1 && $detail->status == '1') {
                $result = ['id' => $detail->minor_classification_id, 'title' => $detail->minor_classification->icon_name];
                $results[] = $result;
            }
        }

        return $results;
    }
}
