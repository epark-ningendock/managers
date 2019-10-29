<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ReservationStoreResource extends Resource
{
    /**
     * 予約完了情報 resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return collect([])
            ->put('status', 0)
            ->put('result_code', $this->result_code)
            ->put('appoint_code', $this->id)
            ->put('gapid', $this->epark_member_id)
            ->put('course_price', $this->fee)
            ->put('endpoint_uri', '')
//            ->put('list', $this->_list($this))
            ->toArray();
    }

    /**
     * https://docknet.backlog.jp/view/APIENGINEDEV-35
     * 空満情報取込APIリクエストパラメータ要素追加
     *
     * @param  予約オプション情報  $reservation_options
     * @return 予約オプション情報
     */
    private function _list() {
        return collect(
            [
            ]);
    }
}
