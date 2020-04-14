<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ConsiderationListShowResource extends Resource
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
            'data' => $this->getdata()
        ];
    } 

    private function getdata() {
            
            $params = [];
            foreach ($this->resource as $result) {
                $param = [
                    'epark_member_id' => $result->epark_member_id,
                    'hospital_id' => $result->hospital_id,
                    'hospital_code' => $result->contract_informations->code,
                    'course_id' => $result->course_id ?? '',
                    'course_code' => $result->course->code ?? '',
                    'display_kbn' => $result->display_kbn
                ];

                $params[] = $param;
            }
            return $params;
        }     
}
