<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseDetailCategoriesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if(!isset($this)) return;

        return [
            [
                'id' => $this->major_classification->id ?? '',
                'title' => $this->major_classification->icon_name ?? '',
                'text' => $this->major_classification->name ?? '',
            ],
            [
                'id' => $this->middle_classification->id ?? '',
                'title' => $this->middle_classification->icon_name ?? '',
                'text' => $this->middle_classification->name ?? '',
            ],
            [
                'id' => $this->minor_classification->id ?? '',
                'title' => $this->minor_classification->icon_name ?? '',
                'text' => $this->minor_classification->name ?? '',
            ],
        ];

    }
}
