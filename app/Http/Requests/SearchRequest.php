<?php

namespace App\Http\Requests;

use App\Http\Requests\ValidationRequest;

use App\Prefecture;
use App\DistrictCode;
use App\Rail;
use App\Station;
use App\HospitalMinorClassification;
use App\MinorClassification;

class SearchRequest extends ValidationRequest
{

    /**
     * 検索API request to JSON
     *
     * @return array
     */
    public function toJson()
    {
        $serach_condition = [
            'freewords' => $this->input('freewords') ?? '',
            'freewords_area' => $this->input('freewords_area') ?? '',
            'freewords_hospital_point' => $this->input('freewords_hospital_point') ?? '',
            'freewords_rail' => $this->input('freewords_rail') ?? '',
            'address_search' => [
                'pref_cd' => $this->input('pref_cd') ?? '',
                'district_no' => $this->input('district_no') ?? '',
                'rail_no' => $this->input('rail_no') ?? '',
                'station_no' => $this->input('station_no') ?? '',
            ],
            'reservation_dt_from' => $this->input('reservation_dt_from') ?? '',
            'reservation_dt_to'  => $this->input('reservation_dt_to') ?? '',
            'price_search' => [
                'price_upper_limit' => $this->input('price_upper_limit') ?? '',
                'price_lower_limit' => $this->input('price_lower_limit') ?? '',
            ],
            'hospital_category_code' => $this->input('hospital_category_code') ?? '',
            'course_category_code' => $this->input('course_category_code') ?? '',
            'site_card' => $this->input('site_card') ?? '',
            'sort_price' => $this->input('course_price_sort') ?? '',
        ];

        $serach_condition_string = [
            'address_search' => [
                'pref_name' => $this->get_pref_name($this->input('pref_cd')),
                'district_name' => $this->get_district_name($this->input('district_no')),
                'rail_name' => $this->get_rail_name($this->input('rail_no')),
                'station_name' => $this->get_station_name($this->input('station_no')),
            ],
            'hospital_category_text' => $this->get_hospital_category_text($this->input('hospital_category_code')),
            'course_category_text' => $this->get_course_category_text($this->input('course_category_code')),
        ];
        return compact('serach_condition', 'serach_condition_string');
    }

    protected function get_pref_name($pref_cd)
    {
        return isset($pref_cd) ? Prefecture::select('name')->find($pref_cd)->name : '';
    }
    protected function get_district_name($district_no)
    {
        if (isset($district_no)) {
            $params = explode(',', $district_no);
            $result = '';
            $distoricts = DistrictCode::whereIn('district_code', $params)->get();
            foreach ($distoricts as $district) {
                $result += $district->name . ',';
            }
            return $result;
        }
        return '';
    }
    protected function get_rail_name($rail_no)
    {
        if (isset($rail_no)) {
            $params = explode(',', $rail_no);
            $result = '';
            $rails = Rail::whereIn('id', $params)->get();
            foreach ($rails as $rail) {
                $result += $rail->name . ',';
            }

            return result;
        }
        return '';
    }
    protected function get_station_name($station_no)
    {
        if (isset($station_no)) {
            $params = explode(',', $station_no);
            $result = '';
            $stations = Station::whereIn('id', $params)->get();
            foreach ($stations as $station) {
                $result += $station->name . ',';
            }
            return $result;
        }
        return '';
    }
    protected function get_hospital_category_text($hospital_category_code)
    {
        if ($hospital_category_code) {
            $array = explode(",", $hospital_category_code);
            $data = HospitalMinorClassification::select('name')->whereIn('id', $array)->get();
            $text = $data->map(function ($d) {
                return $d->name ?? '';
            });
            return $text;
        } else {
            return '';
        }
    }
    protected function get_course_category_text($course_category_code)
    {
        if ($course_category_code) {
            $array = explode(",", $course_category_code);
            $data = MinorClassification::select('name')->whereIn('id', $array)->get();
            $text = $data->map(function ($d) {
                return $d->name ?? '';
            });
            return $text;
        } else {
            return '';
        }
    }
}
