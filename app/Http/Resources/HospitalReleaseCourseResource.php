<?php

namespace App\Http\Resources;

use App\Course;
use App\Enums\Status;
use App\Hospital;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;

class HospitalReleaseCourseResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->resource;
    }

}
