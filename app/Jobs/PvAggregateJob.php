<?php

namespace App\Jobs;

use App\PvRecord;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;

class PvAggregateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $aggregateDate;
    protected $deleteFlg;

    public function __construct($aggregateDate, $deleteFlg)
    {
        $this->$aggregateDate = $aggregateDate;
        $this->deleteFlg = $deleteFlg;
    }

    /**
     *
     */
    public function handle()
    {

        // pv集計数をリセットする
        $query = PvRecord::all();
        $query->update(['pv_count' => 0]);

        // 医療機関ごとの指定日付分のpv数を取得する。
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
