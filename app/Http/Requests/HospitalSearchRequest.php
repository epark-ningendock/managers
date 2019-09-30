<?php

namespace App\Http\Requests;

class HospitalSearchRequest extends SearchRequest
{
    /**
     * 検索API Vlidation rule
     *
     * @return array
     */
    public function rules()
    {
        $flg = $this->input('return_flag');

        return [
            'return_flag' => 'required|numeric',
            'return_from' => $flg == 1 ? 'required|numeric|min:1' : '',
            'return_to' => $flg == 1 ? 'required|numeric|min:1' : '',
            'search_count_only_flag' => 'required|numeric',
            'search_condition_return_flag' => 'required|numeric',
        ];
    }

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

}
