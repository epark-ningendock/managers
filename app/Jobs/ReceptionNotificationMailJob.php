<?php

namespace App\Jobs;

use App\PvRecord;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;

class ReceptionNotificationMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $reservationDates;

    public function __construct($reservationDates)
    {
        $this->$reservationDates = $reservationDates;
    }

    public function handle()
    {
        // 仮受付予約情報をメールする
        $pvRecords = PvRecord::getPvData($this->aggregateDate);

        // 医療機関のpv数を更新する
        $this->updatePvCount($pvRecords);

        // 過のpvデータを削除する
        if ($this->deleteFlg) {
            $this->deletePvData();
        }

    }

    /**
     * 医療機関のPV数を更新する
     * @param array $pvRecords
     */
    protected function updatePvCount(array $pvRecords) {

        foreach ($pvRecords as $pvRecord) {
            $hospital = new Hospital();
            $hospital->id = $pvRecord->hospital_id;
            $hospital->pv_count = $pvRecord->pv;
            $hospital->save();
        }
    }

    /**
     * PVデータを削除する
     */
    protected function deletePvData() {
        $date = Carbon::today();
        $date->subDay(($this->aggregateDate + 3));
        PvRecord::where('created_at', '<=', $date)->delete();

    }
}
