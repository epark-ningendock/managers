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
            'hospital_code' => $this->all(),
        ];
    }

    private function createCodes() {

        if (empty($this)) {
            return [];
        }

        $codes = array();
        foreach ($this as $code) {
            if (empty($code)) {
                continue;
            }
            $codes[] = $code;
        }

        return $codes;
    }
}
