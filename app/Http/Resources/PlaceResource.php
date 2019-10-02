<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlaceResource extends JsonResource
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
                'pref_no' => $this->id,
                'pref_name' => $this->name,
                'count' => $this->hospitals->count(),
                'districts' => DistrictResource::collection($this->district_codes)
            ]
        ];
    }
}
