<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Carbon\Carbon;

class ReservationAllResource extends Resource
{
    /**
     * 予約情報 resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return collect([])
            ->put('status', 0)
//            ->put('result_code', $this->result_code)
            ->put('reservation_ids', $this->create_ids())
            ->toArray();
    }

    /**
     *  予約ID配列を返す
     */
    private function create_ids() {

        $ids = [];
        $reservations = $this['reservations'];
        foreach ($reservations as $reservation) {
            $ids[] = $reservation->id;
        }
        return $ids;
    }
}
