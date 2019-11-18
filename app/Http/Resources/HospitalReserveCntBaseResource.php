<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class HospitalReserveCntBaseResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = $this['data'];

        return collect([])
            ->put('status', 0)
            ->merge($data)
            ->toArray();

    }
}
