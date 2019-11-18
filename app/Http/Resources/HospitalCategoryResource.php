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
        if (!empty($this)) {
            foreach ($this as $detail) {

                if (isset($detail->inputstring) || (isset($detail->select_status) && ($detail->select_status === '1'))) {
                    $result =
                        [
                            'id' => $detail->minor_classification_id,
                            'title' => $detail->minor_classification->icon_name != null ? $detail->minor_classification->icon_name : '',
                            'text' => $detail->inputstring,
                        ];
                    $results[] = $result;
                }
            }

            return $results;
        }
    }
}
