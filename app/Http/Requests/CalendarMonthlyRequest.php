<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Log;
class CalendarMonthlyRequest extends ValidationRequest
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
           // 検査コース空満情報（月別）
           'get_yyyymm_from' => ['nullable','regex:/^2[0-9]{5}$/u'],
           'get_yyyymm_to' =>['nullable','regex:/^2[0-9]{5}$/u'],

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
        $to->addMonthNoOverflow(2);
        $to = $to->endOfMonth();
        if (!empty($this->input('get_yyyymm_from'))) {
            $from = Carbon::createMidnightDate(
                substr($this->input('get_yyyymm_from'), 0, 4),
                substr($this->input('get_yyyymm_from'), -2, 2),
                1);
        }

        if (!empty($this->input('get_yyyymm_to'))) {
            $to = Carbon::createMidnightDate(
                substr($this->input('get_yyyymm_to'), 0, 4),
                substr($this->input('get_yyyymm_to'), -2, 2),
                25);
            $to->endOfMonth();
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
