<?php

namespace App\Http\Resources;

use App\CalendarDay;
use App\Station;
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

        return collect([])
            ->put('no', $this->hospital->id)
            ->put('hospital_code', $this->hospital->contract_information->code ?? '')
            ->merge(parent::baseCollections())
            ->put('month_calender', new MonthlyCalendarResource($this))
            ->put('paycall', $this->hospital->paycall ?? '')
            ->toArray();
    }
}
