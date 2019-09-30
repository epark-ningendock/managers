<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HospitalCategoryResource extends JsonResource
{
    /**
     * 施設分類 into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return !isset($this->hospital_minor_classification) ? [] :
        [
            [
                'id' => $this->hospital_minor_classification->hospital_major_classification->id,
                'title' => $this->hospital_minor_classification->hospital_major_classification->icon_name,
                'text' => $this->hospital_minor_classification->hospital_major_classification->name,
            ],
            [
                'id' => $this->hospital_minor_classification->hospital_middle_classification->id,
                'title' => $this->hospital_minor_classification->hospital_middle_classification->icon_name,
                'text' => $this->hospital_minor_classification->hospital_middle_classification->name,
             ],
            [
                'id' => $this->hospital_minor_classification->id,
                'title' => $this->hospital_minor_classification->icon_name,
                'text' => $this->hospital_minor_classification->name,
            ],
        ];

    }
}
