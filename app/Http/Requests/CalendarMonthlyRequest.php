<?php

namespace App\Http\Requests;

use App\Http\Requests\ValidationRequest;
use Log;
class CalendarMonthlyRequest extends ValidationRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'hospital_code' => 'required|alpha_num',
            'course_no' => 'required|numeric',
            // 検査コース空満情報（月別）
            'get_yyyymm_from' => 'nullable|date_format:Ym',
            'get_yyyymm_to' => 'nullable|date_format:Ym',
            // 検査コース空満情報（日別）
            'get_yyyymmdd_from' => 'nullable|date_format:Ymd',
            'get_yyyymmdd_to' => 'nullable|date_format:Ymd',

        ];
    }

    /**
     * 検査コース空満情報取得API request to array
     *
     * @return array object
     */
    protected function getBetween()
    {
        $now = date('Ymd');
        $from = $this->input('get_yyyymm_from') != null ? $this->input('get_yyyymm_from').'01' : $now;
        $to = $this->input('get_yyyymm_to') != null ? $this->input('get_yyyymm_to').'01' : $now;
        date('Ymd', strtotime('+5 month' . $to));

        // 月初/月末セット
        $firstDate = date('Ymd', strtotime('first day of ' . $from));
        $lastDate = date('Ymd', strtotime('last day of ' . $to));

        return (object) ['from' => $firstDate, 'to' => $lastDate];
    }
    /**
     * 検査コース空満情報取得API request to array
     *
     * @return array object
     */
    public function toObject()
    {
        $days = $this->getBetween();
        Log::debug($days->from);
        Log::debug($days->to);


        return (object) [
            'hospital_code' => $this->input('hospital_code'),
            'course_no' => $this->input('course_no'),
            'get_yyyymmdd_from' => $days->from,
            'get_yyyymmdd_to' => $days->to,
        ];
    }
}
