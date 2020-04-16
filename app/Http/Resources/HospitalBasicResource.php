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
        $hospital_code = '';
        if (isset($this->contract_information) && isset($this->contract_information->code)) {
            $hospital_code = $this->contract_information->code;
        }
        return [
            'status' => $this->status,
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
            'stations' => $this->getStationInfo(),
            'medical_examination_system_id' =>$this->medical_examination_system_id ?? '',
            'kenshin_sys_hospital_no' => $this->kenshin_sys_hospital_id
//            'movie' => $this->getMovieInfo(),
        ];
    }
}
