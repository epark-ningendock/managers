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
        $rails = [$this->rail1, $this->rail2, $this->rail3, $this->rail4, $this->rail5];
        $stations = [$this->station1, $this->station2, $this->station3, $this->station4, $this->station5];
        $accesses = [$this->access1, $this->access2, $this->access3, $this->access4, $this->access5];

        $hospital_code = '';
        if (isset($this->contract_information) && isset($this->contract_information->code)) {
            $hospital_code = $this->contract_information->code;
        }
        return [
            'status' => 0,
            'no' => $this->id,
            'url_basic' => $this->url,
            'hospital_code' => $hospital_code,
            'name' => $this->name,
            'zip_code' => $this->postcode,
            'pref_name' => $this->prefecture->name,
            'district_name' => (isset($this->district_code))? $this->district_code->name : '',
            'address1' => $this->address1,
            'address2' => $this->address2 ?? '',
            'pos_n'=> $this->latitude ?? '',
            'pos_e'=> $this->longitude ?? '',
            'tel' => $this->tel ?? '',
            'tel_ppc'=> $this->paycall ?? '',
            'stations' => Station::getStations($rails, $stations, $accesses),
//            'movie' => $this->getMovieInfo(),
        ];
    }
}
