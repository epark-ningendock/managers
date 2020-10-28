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
            'return_flag' => 'required|numeric|in:0,1',
            'return_from' => $flg == 1 ? 'required|numeric|min:1' : '',
            'return_to' => $flg == 1 ? 'required|numeric|min:1' : '',
            'search_count_only_flag' => 'required|numeric|in:0,1',
            'search_condition_return_flag' => 'required|numeric|in:0,1',

            'freewords' => 'nullable',
            'pref_cd' => 'nullable',
            'district_no' => 'nullable',
            'rail_no' => 'nullable',
            'station_no' => 'nullable',
            'reservation_dt' => 'nullable|date_format:Ymd',
            'price_upper_limit' => 'nullable|numeric',
            'price_lower_limit' => 'nullable|numeric',
            'hospital_category_code' => 'nullable',
            'course_category_code' => 'nullable',
            'site_card' => 'nullable|numeric',
            'exam_type' => 'nullable',
            'disease' => 'nullable',
            'part' => 'nullable',
        ];
    }
    public function withValidator($validator) {
        $validator->after(function ($validator) {
            $return_from = $this->input('return_from');
            $return_to = $this->input('return_to');
            $flg = $this->input('return_flag');
            
            if($flg == 1 && isset($return_from) && isset($return_to)){
                if(($return_to - $return_from) >= 20) {
                    $validator->errors()->add('return_fromt_to',json_encode([
                        'message' => trans('validation.for_api.diff'),
                        'error_no' => '11',
                        'detail_code' => '02',
                    ]));
                }
                if($return_to <= $return_from) {
                    $validator->errors()->add('return_from_to',json_encode([
                        'message' => trans('validation.for_api.order'),
                        'error_no' => '11',
                        'detail_code' => '02',
                    ]));
                }
            }

        });
    }

    /**
     * 検索API request to JSON
     *
     * @return array
     */
    public function toJson()
    {
    		$rawFreeword = mb_convert_encoding($this->input('freewords'), 'utf-8', 'auto');
        $serach_condition = [
            'freewords' => $rawFreeword ?? '',
            'pref_cd' => $this->input('pref_cd') ?? '',
            'district_no' => $this->input('district_no') ?? '',
            'rail_no' => $this->input('rail_no') ?? '',
            'station_no' => $this->input('station_no') ?? '',
            'reservation_dt' => $this->input('reservation_dt') ?? '',
            'price_upper_limit' => $this->input('price_upper_limit') ?? '',
            'price_lower_limit' => $this->input('price_lower_limit') ?? '',
            'hospital_category_code' => $this->input('hospital_category_code') ?? '',
            'course_category_code' => $this->input('course_category_code') ?? '',
            'exam_type' => $this->input('exam_type') ?? '',
            'disease' => $this->input('disease') ?? '',
            'part' => $this->input('part') ?? '',
        ];

        $serach_condition_string = [
            'pref_name' => $this->get_pref_name($this->input('pref_cd')),
            'district_name' => $this->get_district_name($this->input('district_no')),
            'rail_name' => $this->get_rail_name($this->input('rail_no')),
            'station_name' => $this->get_station_name($this->input('station_no')),
            'hospital_category_text' => $this->get_hospital_category_text($this->input('hospital_category_code')),
            'course_category_text' => $this->get_course_category_text($this->input('course_category_code')),
            'exam_type_text' => $this->get_course_category_text($this->input('exam_type')),
            'disease' => $this->get_course_category_text($this->input('disease')),
            'part' => $this->get_course_category_text($this->input('part')),
            'site_card' => $this->get_site_card_name($this->input('site_card')),
        ];
        return compact('serach_condition', 'serach_condition_string');
    }

}
