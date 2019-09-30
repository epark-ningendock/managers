<?php

namespace App\Http\Resources;

use App\Station;

class CourseBasicResource extends CourseBaseResource
{
    /**
     * 検査コース基本情報 resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $rails = [
            $this->hospital->rail1,
            $this->hospital->rail2,
            $this->hospital->rail3,
            $this->hospital->rail4,
            $this->hospital->rail5
        ];

        $stations = [
            $this->hospital->station1,
            $this->hospital->station2,
            $this->hospital->station3,
            $this->hospital->station4,
            $this->hospital->station5
        ];
        $accesses = [
            $this->hospital->access1,
            $this->hospital->access2,
            $this->hospital->access3,
            $this->hospital->access4,
            $this->hospital->access5
        ];

        return collect([])
            ->put('status', 0)        
            ->put('no', $this->id)
            ->put('url_basic', $this->hospital->url)
            ->put('hospital_code', $this->hospital->contract_information->code)
            ->put('name', $this->hospital->name)
            ->put('pref_name', $this->hospital->district_code->prefecture->name)
            ->put('district_name', $this->hospital->district_code->name)
            ->put('address1', $this->hospital->address1)
            ->put('address2', $this->hospital->address2)
            ->put('tel_ppc',  $this->hospital->paycall)
            ->put('stations', Station::getStations($rails, $stations, $accesses))
            ->put('non_consiltation', $this->hospital->consultation_note)
            ->put('non_consultation_note', $this->hospital->memo)
            ->put('hospital_category', HospitalCategoryResource::collection($this->hospital->hospital_details))
            ->merge(parent::baseCollections())
            ->toArray();
    }

}
