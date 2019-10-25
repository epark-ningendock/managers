<?php

namespace App\Http\Resources;

use App\Enums\Status;
use Illuminate\Http\Resources\Json\Resource;

class HospitalCategoryResource extends Resource
{
    /**
     * 施設分類 into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $results = [];
        if (empty($this)) {
            return $results;
        }

        foreach ($this as $detail) {
            if(empty($detail->select_status) || $detail->select_status != Status::VALID) {
                continue;
            }
            $result =
                [
                    'id' => $detail->minor_classification->id,
                    'title' => $this->minor_classification->icon_name,
                    'text' => $this->minor_classification->name,
                ];
            array_merge($results, $result);
        }

        return $results;
    }
}
