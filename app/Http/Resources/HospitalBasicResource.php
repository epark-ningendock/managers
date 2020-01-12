<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use App\Station;

class HospitalBasicResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $rails = [$this->rail1, $this->rail2, $this->rail3, $this->rail4, $this->rail5];
        $stations = [$this->station1, $this->station2, $this->station3, $this->station4, $this->station5];
        $accesses = [$this->access1, $this->access2, $this->access3, $this->access4, $this->access5];

        $hospital_code = '';
        if (isset($this->contract_information) && isset($this->contract_information->code)) {
            $hospital_code = $this->contract_information->code;
        }
        return [
            'status' => 0,
            'no' => $this->id,
            'url_basic' => $this->url,
            'hospital_code' => $hospital_code,
            'name' => $this->name,
            'zip_code' => $this->postcode,
            'pref_name' => $this->prefecture->name,
            'district_name' => (isset($this->district_code))? $this->district_code->name : '',
            'address1' => $this->address1,
            'address2' => $this->address2 ?? '',
            'stations' => Station::getStations($rails, $stations, $accesses),
            'movie' => $this->getMovieInfo(),
        ];
    }

    /**
     * @return array
     */
    private function getMovieInfo() {

        $access_movie = $this->_hospital_movie();
        $one_minutes = $this->getOneMinutes($this->hospital_categories);
        $tour = $this->getTourInterview()[0];
        $interview = $this->getTourInterview()[1];

        return ['access'=>$access_movie, 'oneMinute'=>$one_minutes, 'tour'=>$tour, 'interview'=>$interview];
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
                return $category->hospital_image->memo1;
            }
        }

        return '';
    }

    /**
     * 1分動画
     *
     * @param  医療機関カテゴリ
     * @return
     */
    private function getOneMinutes($hospital_categories) {

        if(!isset($hospital_categories)) {
            return '';
        }

        $el = $hospital_categories->first(function ($v) {
            return isset($v->image_order)
                && $v->image_order === 3
                && isset($v->file_location_no)
                && $v->file_location_no === 1;
        });

        if (isset($el) && isset($el->caption)) {
            $strs = explode(' ', $el->caption);
            foreach ($strs as $str) {
                if (strstr($str, 'src=') === false) {
                    continue;
                }
                $str = mb_ereg_replace('\"', '\'', $str);

                $one_minute_url = ltrim($str, 'src=\'');

                return rtrim($one_minute_url,'\'');
            }
        } else {
            return "";
        }
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    private function getTourInterview() {

        $free_area_strs = explode(' ', $this->free_area);
        $tour = '';
        $interview = '';

        foreach ($free_area_strs as $str) {
            if (preg_match('/src/', $str) && preg_match('/grooon/', $str)) {
                $str = ltrim($str, 'src=');
                $str = mb_ereg_replace('\'', '', $str);
                $tour = mb_ereg_replace('\"', '', $str);
            }
            if (preg_match('/src/', $str) && !preg_match('/grooon/', $str)) {
                $str = ltrim($str, 'src=');
                $str = mb_ereg_replace('\'', '', $str);
                $interview = mb_ereg_replace('\"', '', $str);
            }
        }

        return collect([$tour, $interview]);
    }
}
