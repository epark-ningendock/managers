<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class DailyCalendarResource extends Resource
{
    /**
     * 検査コース（日別） resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'yyyymmdd' => date('Ymd', strtotime($this->date)),
            'appoint_status' => $this->appoint_status,
            'reservation_frames' => $this->reservation_frames,
            'appoint_num' => $this->appoint_num,
            'closed_day' => $this->closed_day === 0 ? 0 : 1,
        ];
    }
}
