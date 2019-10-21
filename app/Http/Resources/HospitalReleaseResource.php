<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use App\Station;

class HospitalReleaseResource extends Resource
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
            'hospital_code' => $this->createCodes()
        ];
    }

    private function createCodes() {

        if (empty($this)) {
            return [];
        }

        $codes = [];
        foreach ($this as $code) {
            if (empty($code)) {
                continue;
            }
            array_push($codes, $code);
        }

        return $codes;
    }
}
