<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class DistrictResource extends Resource
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
            'district_no' => $this->code,
            'district_name' => $this->name,
            'count' => $this->hospitals->count()
        ];
    }
}
