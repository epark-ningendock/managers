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
        $rail_ids = [];
        if (isset($this->rail1)) {
            $rail1 = $this->rail1;
            $rail_ids[] = $rail1;
        }
        if (isset($this->rail2)) {
            $rail2 = $this->rail2;
            $rail_ids[] = $rail2;
        }
        if (isset($this->rail3)) {
            $rail3 = $this->rail3;
            $rail_ids[] = $rail3;
        }
        if (isset($this->rail4)) {
            $rail4 = $this->rail4;
            $rail_ids[] = $rail4;
        }
        if (isset($this->rail5)) {
            $rail5 = $this->rail5;
            $rail_ids[] = $rail5;
        }

        $station_ids = [];
        if (isset($this->station1)) {
            $station1 = $this->station1;
            $station_ids[] = $station1;
        }
        if (isset($this->station2)) {
            $station2 = $this->station2;
            $station_ids[] = $station2;
        }
        if (isset($this->station3)) {
            $station3 = $this->station3;
            $station_ids[] = $station3;
        }
        if (isset($this->station4)) {
            $station4 = $this->station4;
            $station_ids[] = $station4;
        }
        if (isset($this->station5)) {
            $station5 = $this->station5;
            $station_ids[] = $station5;
        }
        $accesses = [];
        if (isset($this->access1)) {
            $access1 = $this->access1;
            $accesses[] = $access1;
        }
        if (isset($this->access2)) {
            $access2 = $this->access2;
            $accesses[] = $access2;
        }
        if (isset($this->access3)) {
            $access3 = $this->access3;
            $accesses[] = $access3;
        }
        if (isset($this->access4)) {
            $access4 = $this->access4;
            $accesses[] = $access4;
        }
        if (isset($this->access5)) {
            $access5 = $this->access5;
            $accesses[] = $access5;
        }

//        $rail_ids = [$this->rail1, $this->rail2, $this->rail3, $this->rail4, $this->rail5];
//        $station_ids = [$this->station1, $this->station2, $this->station3, $this->station4, $this->station5];
//        $accesses = [$this->access1, $this->access2, $this->access3, $this->access4, $this->access5];

        return [
            'status' => 0,
            'no' => $this->id,
            'url_basic' => $this->url,
            'hospital_code' => $this->contract_information->code,
            'name' => $this->name,
            'zip_code' => $this->postcode,
            'pref_name' => $this->prefecture->name,
            'district_name' => (isset($this->district_code))? $this->district_code->name : '',
            'address1' => $this->address1,
            'address2' => $this->address2,
            'pos_n' => $this->latitude,
            'pos_e' => $this->longitude,
            'streetview_url' => $this->streetview_url,
            'tel' => $this->tel,
            'tel_ppc' => $this->paycall,
            'stations' => Station::getStations($rail_ids, $station_ids, $accesses),
            'open' => OpenResource::collection($this->medical_treatment_times),
            'non_consultation' => $this->consultation_note,
            'non_consultation_note' => $this->memo,
            'public_status' => $this->status,
            'update_at' => $this->updated_at,
        ];
    }
}
