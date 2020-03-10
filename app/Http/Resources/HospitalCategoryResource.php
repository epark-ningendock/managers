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
        if (!empty($this->hospital_details)) {
            foreach ($this->hospital_details as $detail) {

                if (!empty($detail->inputstring) || (isset($detail->select_status) && ($detail->select_status == 1))) {

                    if (empty($detail->inputstring)) {
                        $text = $detail->minor_classification->name;
                    } else {
                        $text = $detail->inputstring;
                    }


                    $result =
                        [
                            'id' => $detail->minor_classification_id,
                            'title' => $detail->minor_classification->icon_name != null ? $detail->minor_classification->icon_name : '',
                            'text' => $text,
                        ];
                    if (!empty($result)) {
                        $results[] = $result;
                    }

                }
            }

            return $results;
        }
    }
}
