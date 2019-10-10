<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class HospitalCoursesResource extends Resource
{
    /**
     * 医療機関検査コース一覧 リソースクラス
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'status' => 0,
            'no' => $this->id,
            'hospital_code' => $this->contract_information->code,
            'courses' => CoursesResource::collection($this->courses),
        ];
    }
}
