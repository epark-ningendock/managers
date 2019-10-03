<?php

namespace App\Http\Requests;

use App\Http\Requests\CalendarMonthlyRequest;

class CalendarDayRequest extends CalendarMonthlyRequest
{

    /**
     * 検査コース空満情報取得API request to array
     *
     * @return array object
     */
    protected function getBetween() {

        $now = date("Ymd");
        $from = $this->input('get_yyyymmdd_from') != null ? $this->input('get_yyyymmdd_from') : $now;
        $to = $this->input('get_yyyymmdd_to') != null ? $this->input('get_yyyymmdd_to') : date('Ymd', strtotime('+5 month' . $now));
        return (object)['from' => $from, 'to' => $to];
    }
}