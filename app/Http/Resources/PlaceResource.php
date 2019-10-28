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
            'place' => $this->createPref()
        ];

    }

    private function createPref() {
        $data = $this['place_data'];
        $place_code = $this['place_code'];

        $results = [];
        foreach ($data as $pref) {
            $result = [
                'pref_no' => $pref->id,
                'pref_name' => $pref->name
            ];
            if (!empty($place_code) && $place_code != 0) {
                array_push($result, ['districts' => DistrictResource::collection($pref->district_codes)]);
            }

            $results[] = $result;

        }

        return $results;
    }
}
