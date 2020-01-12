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
        // おすすめ
        $recommended = [];
        foreach ($this->course_details as $detail) {
            if ($detail->major_classification_id == 24 && !empty($detail->inputstring)) {
                array_push($recommended, $detail->inputstring);
            }
        }

        $rails = [$this->hospital->rail1, $this->hospital->rail2, $this->hospital->rail3, $this->hospital->rail4, $this->hospital->rail5];
        $stations = [$this->hospital->station1, $this->hospital->station2, $this->hospital->station3, $this->hospital->station4, $this->hospital->station5];
        $accesses = [$this->hospital->access1, $this->hospital->access2, $this->hospital->access3, $this->hospital->access4, $this->hospital->access5];

        // calendar_days追加要素セット
//        $_courses = parent::modifyCalendarDays($this);

        return $this->baseCollections()
            ->put('no', $this->hospital->id)
            ->put('hospital_code', $this->hospital->contract_information->code ?? '')
            ->put('name', $this->hospital->name)
            ->put('pref_name', $this->hospital->prefecture->name)
            ->put('district_name', $this->hospital->districtCode->name)
            ->put('address1', $this->hospital->address1)
            ->put('address2', $this->hospital->address2)
            ->put('stations', Station::getStations($rails, $stations, $accesses))
            ->put('paycall', $this->hospital->paycall ?? '')
            ->toArray();
    }
}
