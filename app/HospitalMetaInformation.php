<?php

namespace App;

use App\Enums\Status;
use App\Enums\WebReception;
use Illuminate\Database\Eloquent\Model;

class HospitalMetaInformation extends Model
{

    protected $table = 'hospital_meta_information_view';

    public function hospitals()
    {
        return $this->belongsTo('App\Hospital');
    }

    public function courses()
    {
        return $this->hasMany('App\Course');
    }

    public function hospital_images()
    {
        return $this->hasMany('App\HospitalImage');
    }

    public function hospital_categories()
    {
        return $this->hasMany('App\HospitalCategory');
    }

    public function hospital_details()
    {
        return $this->hasMany('App\HospitalDetail');
    }

    public function contract_information()
    {
        return $this->hasOne('App\ContractInformation', 'hospital_id', 'id');
    }

    /**
     * 医療機関一覧検索
     *
     * @return クエリ
     */
    public function scopeWhereForSearchAPI($query, $request)
    {
        if ($request->input('freewords') != null) {
						$rawFreeword = mb_convert_encoding($request->input('freewords'), 'utf-8', 'auto');
						$freeword_str = str_replace('　', ' ', $rawFreeword);
            $freewords = explode(' ', $freeword_str);
            // フリーワード（地名など）
            $query->where(function ($q) use ($freewords) {
                $q->where('area_station', 'like', '%' . $freewords[0] . '%');
                for ($i = 1; $i < count($freewords); $i++) {
                    $q->orWhere('area_station', 'like', '%' . $freewords[$i] . '%');
                }
            });

//            $query->orWhereHas('prefecutres', function ($query) use ($freewords) {
//                $query->where('name', 'like', '%' . $freewords[0] . '%');
//                for ($i = 1; $i < count($freewords); $i++) {
//                    $query->orWhere('name', 'like', '%' . $freewords[$i] . '%');
//                }
//            });
//
//            $query->orWhereHas('district_codes', function ($query) use ($freewords) {
//                $query->where('name', 'like', '%' . $freewords[0] . '%');
//                for ($i = 1; $i < count($freewords); $i++) {
//                    $query->orWhere('name', 'like', '%' . $freewords[$i] . '%');
//                }
//            });
//
//            $query->orWhere(function ($q) use ($freewords) {
//                $q->where('address1', 'like', '%' . $freewords[0] . '%');
//                for ($i = 1; $i < count($freewords); $i++) {
//                    $q->orWhere('address1', 'like', '%' . $freewords[$i] . '%');
//                }
//            });
//
//            // 駅
//            $query->orWhereHas('rails', function ($query) use ($freewords) {
//                $query->where('name', 'like', '%' . $freewords[0] . '%');
//                for ($i = 1; $i < count($freewords); $i++) {
//                    $query->orWhere('name', 'like', '%' . $freewords[$i] . '%');
//                }
//            });
//
//            // フリーワード（施設名など）
//            $query->orWhere(function ($q) use ($freewords) {
//                $q->where('hospital_name', 'like', '%' . $freewords[0] . '%');
//                for ($i = 1; $i < count($freewords); $i++) {
//                    $q->orWhere('hospital_name', 'like', '%' . $freewords[$i] . '%');
//                }
//            });

            // フリーワード（検査種別）
            $query->orWhere(function ($q) use ($freewords) {
                $q->where('category_exam_name', 'like', '%' . $freewords[0] . '%');
                for ($i = 1; $i < count($freewords); $i++) {
                    $q->orWhere('category_exam_name', 'like', '%' . $freewords[$i] . '%');
                }
            });

            // フリーワード（検査内容）
            $query->orWhere(function ($q) use ($freewords) {
                $q->where('category_part_name', 'like', '%' . $freewords[0] . '%');
                for ($i = 1; $i < count($freewords); $i++) {
                    $q->orWhere('category_part_name', 'like', '%' . $freewords[$i] . '%');
                }
            });

        }

        // 都道府県コード
        $pref_cd = $request->input('pref_cd');
        if (isset($pref_cd)) {
            $query->whereHas('hospitals', function ($query) use ($pref_cd) {
                $query->where('prefecture_id', $pref_cd);
            });
        };

        // 市区町村コード
        $district_no = $request->input('district_no');
        if (isset($district_no)) {
            $districts = explode(',', $district_no);
            $query->whereHas('hospitals', function ($query) use ($districts) {
                $query->whereIn('district_code', $districts);
            });
        };

        // 路線コード
        $rail_no = $request->input('rail_no');
        if (isset($rail_no)) {
            $rails = explode(',', $rail_no);
            $query->whereHas('hospitals', function ($q) use ($rails) {
                $q->whereIn('rail1', $rails)
                    ->orWhereIn('rail2', $rails)
                    ->orWhereIn('rail3', $rails)
                    ->orWhereIn('rail4', $rails)
                    ->orWhereIn('rail5', $rails);
            });
        };

        // 駅コード
        $station_no = $request->input('station_no');
        if (isset($station_no)) {
            $stations = explode(',', $station_no);
            $query->whereHas('hospitals', function ($q) use ($stations) {
                $q->whereIn('station1', $stations)
                    ->orWhereIn('station2', $stations)
                    ->orWhereIn('station3', $stations)
                    ->orWhereIn('station4', $stations)
                    ->orWhereIn('station5', $stations);
            });
        };

        $reservation_dt = $request->input('reservation_dt');
        if (isset($reservation_dt)) {
            $query->whereExists(function ($q) use ($reservation_dt) {
                $q->select(DB::raw(1))
                    ->from('courses.calendar_days')
                    ->where('courses.calendar_days', $reservation_dt)
                    ->where('courses.calendar_days.is_reservation_acceptance', 1)
                    ->whereRaw('courses.calendar_days.reservation_frames > courses.calendar_days.reservation_count');
            });
        }

        // 検査コース金額検索
        $price_upper_limit = $request->input('price_upper_limit');
        if (isset($price_upper_limit)) {
            $query->whereExists(function ($q) use ($price_upper_limit) {
                $q->select(DB::raw(1))
                    ->from('courses')
                    ->where('courses.price', '<=', $price_upper_limit)
                    ->where('courses.web_reception', WebReception::ACCEPT);
            });
        }
        $price_lower_limit = $request->input('price_lower_limit');
        if (isset($price_lower_limit)) {
            $query->whereExists(function ($q) use ($price_lower_limit) {
                $q->select(DB::raw(1))
                    ->from('courses')
                    ->where('courses.price', '>=', $price_lower_limit)
                    ->where('courses.web_reception', WebReception::ACCEPT);
            });
        }

        // 施設分類コード
        if ($request->input('hospital_category_code') !== null) {
            $hospital_categories = explode(",", $request->input('hospital_category_code'));
            // クレジットカード
            if (in_array('5', $hospital_categories)) {
                $query->whereHas('hospital_details', function ($query)  {
                    $query->whereIn('minor_classification_id', 5);
                    $query->whereNotNull('inputstring');
                });
            }

            // 駐車場あり、送迎あり、子連れOK、専用フロア
            $query->whereHas('hospital_details', function ($query) use ($hospital_categories)  {
                $query->whereIn('minor_classification_id', $hospital_categories);
                $query->where('select_status', Status::VALID);
            });

//            // 送迎あり
//            if (in_array('3', $hospital_categories)) {
//                $query->whereHas('hospital_details', function ($query)  {
//                    $query->whereIn('minor_classification_id', 3);
//                    $query->where('select_status', Status::VALID);
//                });
//            }
//
//            // 子連れOK
//            if (in_array('16', $hospital_categories)) {
//                $query->whereHas('hospital_details', function ($query)  {
//                    $query->whereIn('minor_classification_id', 16);
//                    $query->where('select_status', Status::VALID);
//                });
//            }
//
//            // 専用フロア
//            if (in_array('19', $hospital_categories)) {
//                $query->whereHas('hospital_details', function ($query)  {
//                    $query->whereIn('minor_classification_id', 19);
//                    $query->where('select_status', Status::VALID);
//                });
//            }

        }

        // 検査コース分類コード
        if ($request->input('course_category_code') !== null) {
            $course_categories = explode(",", $request->input('course_category_code'));
            $query->whereHas('courses.course_details', function ($query) use ($course_categories) {
                $query->whereIn('minor_classification_id', $course_categories);
            });
        }

//        Log::debug($query->toSql());

        return $query;
    }
}
