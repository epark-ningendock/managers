<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use App\Station;

class HospitalBasicResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $rail_ids = [$this->rail1, $this->rail2, $this->rail3, $this->rail4, $this->rail5];
        $station_ids = [$this->station1, $this->station2, $this->station3, $this->station4, $this->station5];
        $accesses = [$this->access1, $this->access2, $this->access3, $this->access4, $this->access5];

        return [
            'status' => 0,
            'no' => $this->id,
            'url_basic' => $this->url,
            'hospital_code' => $this->contract_information->code,
            'name' => $this->name,
            'zip_code' => $this->postcode,
            'pref_name' => $this->prefecture->name,
            'district_name' => (isset($this->contract_information))? $this->contract_information->code : '',
            'address1' => $this->address1,
            'address2' => $this->address2,
            'pos_n' => $this->longitude,
            'pos_e' => $this->latitude,
            'streetview_url' => $this->streetview_url,
            'tel_ppc' => $this->paycall,
            'stations' => Station::getStations($rail_ids, $station_ids, $accesses),
            'open' => OpenResource::collection($this->medical_treatment_times),
            'non_consultation' => $this->consultation_note,
            'non_consultation_note' => $this->memo,
        ];
    }
}
