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
            $this[0]->hospital->rail1,
            $this[0]->hospital->rail2,
            $this[0]->hospital->rail3,
            $this[0]->hospital->rail4,
            $this[0]->hospital->rail5
        ];

        $stations = [
            $this[0]->hospital->station1,
            $this[0]->hospital->station2,
            $this[0]->hospital->station3,
            $this[0]->hospital->station4,
            $this[0]->hospital->station5
        ];
        $accesses = [
            $this[0]->hospital->access1,
            $this[0]->hospital->access2,
            $this[0]->hospital->access3,
            $this[0]->hospital->access4,
            $this[0]->hospital->access5
        ];

        return collect([])
            ->put('status', 0)        
            ->put('no', $this[0]->id)
            ->put('url_basic', $this[0]->hospital->url)
            ->put('hospital_code', $this[0]->hospital->contract_information->code)
            ->put('name', $this[0]->hospital->name)
            ->put('pref_name', $this[0]->hospital->district_code->prefecture->name)
            ->put('district_name', $this[0]->hospital->district_code->name)
            ->put('address1', $this[0]->hospital->address1)
            ->put('address2', $this[0]->hospital->address2)
            ->put('tel',  $this[0]->hospital->tel)
            ->put('tel_ppc',  $this[0]->hospital->paycall)
            ->put('stations', Station::getStations($rails, $stations, $accesses))
            ->put('non_consiltation', $this[0]->hospital->consultation_note)
            ->put('non_consultation_note', $this[0]->hospital->memo)
            ->put('public_status', $this[0]->hospital->status)
            ->put('update_at', $this[0]->hospital->updated_at)
            ->put('hospital_category', HospitalCategoryResource::collection($this[0]->hospital->hospital_details))
            ->merge(parent::baseCollections())
            ->toArray();
    }

}
