<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class HospitalIndexResource extends Resource
{
    /**
     * 医療機関情報 into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return collect([])
            ->merge(new HospitalBasicResource($this))
            ->merge(new HospitalContentBaseResource($this))
            ->put('courses', CoursesResource::collection($this->courses))
            ->toArray();
    }
}
