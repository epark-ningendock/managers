<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class CoursesReleaseResource extends Resource
{
    /**
     * 公開中コース一覧 into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return collect([])
            ->put('course_code', $this->code)
            ->put('course_no', $this->id)
            ->toArray();
    }
}
