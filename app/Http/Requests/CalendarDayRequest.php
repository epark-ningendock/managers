<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Log;

class CalendarDayRequest extends ValidationRequest
{

//    /**
//     * Get the validation rules that apply to the request.
//     *
//     * @return array
//     */
//    public function rules()
//    {
//        return [
//            'hospital_code' => 'required|alpha_num',
//            'course_no' => 'required|numeric',
//            // 検査コース空満情報（日別）
//            'get_yyyymmdd_from' => 'nullable|date_format:Ymd',
//            'get_yyyymmdd_to' => 'nullable|date_format:Ymd',
//
//        ];
//    }

    /**
     * 検査コース空満情報取得API request to array
     *
     * @return array object
     */
    protected function getBetween()
    {
        $from = Carbon::today()->firstOfMonth();
        $to = Carbon::today();
        $to->addMonthNoOverflow();
        $to = $to->endOfMonth();

        if (!empty($this->input('get_yyyymmdd_from'))) {
            $from = Carbon::createMidnightDate(
                substr($this->input('get_yyyymmdd_from'), 0, 4),
                substr($this->input('get_yyyymmdd_from'), 4, 2),
                substr($this->input('get_yyyymmdd_from'), 6, 2));
        }

        if (!empty($this->input('get_yyyymmdd_to'))) {
            $to = Carbon::createMidnightDate(
                substr($this->input('get_yyyymmdd_to'), 0, 4),
                substr($this->input('get_yyyymmdd_to'), 4, 2),
                substr($this->input('get_yyyymmdd_to'), 6, 2));
        }

        return (object) ['from' => $from, 'to' => $to];
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
            'course_code' => $this->input('course_code'),
            'get_yyyymmdd_from' => $days->from,
            'get_yyyymmdd_to' => $days->to,
        ];
    }
}
