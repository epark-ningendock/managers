<?php

namespace App\Http\Resources;

use App\MedicalTreatmentTime;
use Carbon\Carbon;
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

        if (!empty($this->district_code) && !empty($this->district_code->name)) {
            $pref_name = $this->district_code->name;
        } else {
            $pref_name = '';
        }
        return [
            'no' => $this->id,
            'url_basic' => $this->url,
            'hospital_code' => $this->contract_information->code,
            'name' => $this->name,
            'update_dt' => Carbon::parse($this->updated_at)->format('Y年m月d日'),
            'pref_name' => $this->prefecture->name,
            'district_name' => $pref_name,
            'address1' => $this->address1 ?? '',
            'address2' => $this->address2 ?? '',
            'stations' => $this->getStationInfo(),
            'closed_day' => $this->getClosedDay(),
            'non_consultation' => $this->consultation_note ?? '',
            'non_consultation_note' => $this->memo ?? '',
            'img_sub' => $this->getImgSub($this->hospital_categories),
            'movie' => $this->getMovieInfo(),
            'caption' => $this->getCaption($this->hospital_categories),
            'category' => new HospitalCategoryResource($this),
            'pickup' => $this->is_pickup,
            'courses' => CourseBaseResource::collection($this->courses),
        ];
    }

    /**
     * @return string
     */
    private function getClosedDay() {

        $mon_flg = false;
        $tue_flg = false;
        $wed_flg = false;
        $thu_flg = false;
        $fri_flg = false;
        $sat_flg = false;
        $sun_flg = false;
        $hol_flg = false;
        foreach ($this->medical_treatment_times as $entity) {
            if ($entity->mon == 1) {
                $mon_flg = true;
            }
            if ($entity->tue == 1) {
                $tue_flg = true;
            }
            if ($entity->wed == 1) {
                $wed_flg = true;
            }
            if ($entity->thu == 1) {
                $thu_flg = true;
            }
            if ($entity->fri == 1) {
                $fri_flg = true;
            }
            if ($entity->sat == 1) {
                $sat_flg = true;
            }
            if ($entity->sun == 1) {
                $sun_flg = true;
            }
            if ($entity->hol == 1) {
                $hol_flg = true;
            }
        }

        $result = '';
        if (!$mon_flg) {
            $result = '月・';
        }
        if (!$tue_flg) {
            $result = $result . '火・';
        }
        if (!$wed_flg) {
            $result = $result . '水・';
        }
        if (!$thu_flg) {
            $result = $result . '木・';
        }
        if (!$fri_flg) {
            $result = $result . '金・';
        }
        if (!$sat_flg) {
            $result = $result . '土・';
        }
        if (!$sun_flg) {
            $result = $result . '日・';
        }
        if (!$hol_flg) {
            $result = $result . '祝・';
        }

        return rtrim($result, '・');
    }

    private function getMovieInfo() {

        $access_movie = $this->_hospital_movie();
        $one_minutes = $this->getOneMinutes($this->hospital_categories);
        $tour = $this->getTourInterview()[0];
        $interview = $this->getTourInterview()[1];
//        $tour = strpos($this->free_area, '<div class=¥"movieArea¥">') == false ? 0 : 1;
//        $interview = strpos($this->free_area, '<div class=¥"<div class=¥"movieArea grooon¥">') == false ? 0 : 1;

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
                return 1;
            }
        }

        return 0;
    }

    /**
     * 1分動画
     *
     * @param  医療機関カテゴリ
     * @return
     */
    private function getOneMinutes($hospital_categories) {

        if(!isset($hospital_categories)) {
            return 0;
        }

        $el = $hospital_categories->first(function ($v) {
            return isset($v->image_order)
                && $v->image_order === 3
                && isset($v->file_location_no)
                && $v->file_location_no === 1;
        });

        if (isset($el) && isset($el->caption)) {
            return preg_match('/src/',$el->caption);
        } else {
            return 0;
        }

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

        if (isset($el) && isset($el->caption)) {
            if (strlen($el->caption) > 254) {
                return mb_strcut($el->caption, 0 , 254, 'UTF-8') . '...';
            } else {
                return $el->caption ?? '';
            }

        } else {
            return '';
        }
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

        foreach ($hospital_categories as $category) {
            if (isset($category->image_order)
            && ($category->image_order === 2)
            && ($category->file_location_no == 1)
                && !empty($category->hospital_image->path)) {
                return ['img_sub_url' => $category->hospital_image->path, 'img_sub_alt' => $category->hospital_image->memo1 ?? ''];
            }
        }

        return ['img_sub_url' => '', 'img_sub_alt' => ''];
    }

}
