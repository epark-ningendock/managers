<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class HospitalShopownerResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'status' => 0,
            'hospital_code' => $this->contract_information->code,
            'shopowner_id' => $this->old_karada_dog_id,
        ];
    } 
}
