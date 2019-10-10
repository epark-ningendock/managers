<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use App\Station;

class SearchHospitalsResource extends Resource
{
    /**
     * 医療機関一覧検索 リソースクラス
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $rails = [$this->rail1, $this->rail2, $this->rail3, $this->rail4, $this->rail5];
        $stations = [$this->station1, $this->station2, $this->station3, $this->station4, $this->station5];
        $accesses = [$this->access1, $this->access2, $this->access3, $this->access4, $this->access5];

        return [
            'no' => $this->id,
            'url_basic' => $this->url,
            'hospital_code' => (isset($this->contract_information))? $this->contract_information->code : '',
            'name' => $this->name,
            'update_dt' => $this->update_at,
            'pref_name' => $this->prefecture->name,
            'district_name' => $this->districtCode->name,
            'address1' => $this->address1,
            'address2' => $this->address2,
            'stations' => Station::getStations($rails, $stations, $accesses),
            'non_consultation' => $this->consultation_note,
            'non_consultation_note' => $this->memo,
            'img_sub' => ImagePathsResource::collection($this->getImgSub($this->hospital_categories)),
            'caption' => $this->getCaption($this->hospital_categories),
            'category' => HospitalCategoryResource::collection($this->hospital_details),
            'pickup' => $this->is_pickup,
            'courses' => CoursesBaseResource::collection($this->courses),
        ];
    }

    /**
     * キャプション取得
     *
     * @param  医療機関カテゴリ
     * @return キャプション
     */
    private function getCaption($hospital_categories) {

        if(!isset($hospital_categories)) return '';

        $el = $hospital_categories->first(function ($v) {
            return isset($v->image_order) 
                && isset($v->image_order->image_group_number)
                && $v->image_order->image_group_number === 3
                && isset($v->image_order->image_location_number)
                && $v->image_order->image_location_number === 1;
        });

        return $el->caption ?? '';
    }

    /**
     * サブメイン画像取得
     *
     * @param  医療機関カテゴリ
     * @return サブメイン画像
     */
    private function getImgSub($hospital_categories) {

        if(!isset($hospital_categories)) return '';

        $elemens = $hospital_categories->map(function ($v) {
            if (isset($v->image_order) 
                && isset($v->image_order->image_group_number)
                && $v->image_order->image_group_number === 2) {
                    return $v->hospital_image;
                }
        });
        return $elemens ?? null;
    }

}
