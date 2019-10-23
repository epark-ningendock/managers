<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class PlaceResource extends Resource
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
            'place' => [
                'pref_no' => $this[0]->id,
                'pref_name' => $this[0]->name,
                'count' => $this[0]->hospitals->count(),
                'districts' => DistrictResource::collection($this->district_codes)
            ]
        ];
    }
}
