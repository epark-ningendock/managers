<?php

namespace App\Http\Resources;

use App\Enums\Status;

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
            ->put('sho_name', $this->createShoname())
            ->merge(parent::baseCollections())
            ->toArray();
    }

    /**
     * @return string
     *
     */
    private function createShoname() {
        $result = '';
        foreach ($this->course_details as $detail) {
            if ($detail->major_classification_id == 13
                && $detail->middle_classification_id == 30
                && $detail->select_status == 1
                && $detail->status == Status::VALID) {
                $result = $result . $detail->minor_classification->name . ',';
            }
        }

        if (!empty($result)) {
            return rtrim($result, ',');
        }

        return $result;
    }
}
