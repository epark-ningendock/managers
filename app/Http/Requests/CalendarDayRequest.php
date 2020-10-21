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
   public function rules()
   {
       return [
           'hospital_code' => ['required','regex:/^D[0-9a-zA-Z]+$/u','exists:contract_informations,code'],
           'course_code' => 'required|alpha_num|exists:courses,code',
           // 検査コース空満情報（日別）
           'get_yyyymmdd_from' => ['nullable','numeric','regex:/^2[0-9]{7}$/u'],
           'get_yyyymmdd_to' => ['nullable','numeric','regex:/^2[0-9]{7}$/u'],

       ];
   }

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

        return (object) [
            'hospital_code' => $this->input('hospital_code'),
            'course_code' => $this->input('course_code'),
            'get_yyyymmdd_from' => $days->from,
            'get_yyyymmdd_to' => $days->to,
        ];
    }
}
