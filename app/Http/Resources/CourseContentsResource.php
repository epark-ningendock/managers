<?php

namespace App\Http\Resources;

class CourseContentsResource extends CourseContentBaseResource
{
    /**
     * 検査コースコンテンツ情報 resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return collect([])
            ->put('status', 0)        
            ->put('no', $this->hospital->id)
            ->put('hospital_code', $this->hospital->contract_information->code)
            ->put('course_no', $this->id)
            ->put('course_code', $this->code)
            ->merge(parent::baseCollections())
            ->toArray();
    }

}
