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
            'movie' => $this->getMovieInfo(),
            'caption' => $this->getCaption($this->hospital_categories),
            'category' => HospitalCategoryResource::collection($this->hospital_details),
            'pickup' => $this->is_pickup,
            'courses' => CoursesBaseResource::collection($this->courses),
        ];
    }

    private function getCategory() {
        $categories = HospitalCategoryResource::collection($this->hospital_details);

        $results = [];
        foreach ($categories as $category) {
            if (empty($category)) {
                continue;
            }
            $results[] = $category;
        }
        return $results;
    }

    private function getMovieInfo() {

        $access_movie = $this->_hospital_movie();
        $caption = $this->getCaption($this->hospital_categories);
        $tour = $this->getTourInterview()[0];
        $interview = $this->getTourInterview()[1];
//        $tour = strpos($this->free_area, '<div class=¥"movieArea¥">') == false ? 0 : 1;
//        $interview = strpos($this->free_area, '<div class=¥"<div class=¥"movieArea grooon¥">') == false ? 0 : 1;

        return ['access'=>$access_movie, 'oneMinute'=>$caption, 'tour'=>$tour, 'interview'=>$interview];

    }

    /**
     * アクセス動画URL
     *
     * @param  医療機関カテゴリ
     * @return 医療施設メイン
     */
    private function _hospital_movie()
    {
        $categories = $this->hospital_categories->filter(function ($c) {
            return isset($c->image_order)
                && $c->image_order === 4;
        });

        foreach ($categories as $category) {
            if (!empty($category->hospital_image->memo1)) {
                return 1;
            }
        }

        return 0;
    }

    /**
     * キャプション取得
     *
     * @param  医療機関カテゴリ
     * @return
     */
    private function getCaption($hospital_categories) {

        if(!isset($hospital_categories)) {
            return 0;
        }

        $el = $hospital_categories->first(function ($v) {
            return isset($v->image_order)
                && $v->image_order === 3
                && isset($v->file_location_no)
                && $v->file_location_no === 1;
        });

        return preg_match('/src/',$el->caption);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    private function getTourInterview() {

        $free_area_strs = explode(' ', $this->free_area);
        $tour = 0;
        $interview = 0;

        foreach ($free_area_strs as $str) {
            if (preg_match('/src/', $str) && preg_match('/grooon/', $str)) {
                $tour = 1;
            }
            if (preg_match('/src/', $str) && !preg_match('/grooon/', $str)) {
                $interview = 1;
            }
        }

        return collect([$tour, $interview]);
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
                && $v->image_order === 2) {
                    return $v->hospital_image;
                }
        });
        return $elemens ?? null;
    }

}
