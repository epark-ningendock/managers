<?php

namespace App\Http\Requests;

class CourseSearchRequest extends SearchRequest
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
            'course_price_sort' => 'required|numeric|in:0,1',

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
                    $validator->errors()->add('return_from_to',json_encode([
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
    
}