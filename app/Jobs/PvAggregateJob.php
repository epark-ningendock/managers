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
        $this->aggregateDate = $aggregateDate;
        $this->deleteFlg = $deleteFlg;
    }

    /**
     *
     */
    public function handle()
    {

        // pv集計数をリセットする
        $this->resetPvCount();

        // 医療機関ごとの指定日付分のpv数を取得する。
        $query = PvRecord::getPvData($this->aggregateDate);
        $pvRecords = collect($query->get()->toArray());

        // 医療機関のpv数を更新する
        if (count($pvRecords)) {
            $this->updatePvCount($pvRecords);
        }

        // 過のpvデータを削除する
        if ($this->deleteFlg) {
            $this->deletePvData();
        }

    }

    /**
     * PV数をリセットする
     */
    protected function resetPvCount() {
        $pvRecords = PvRecord::all();
        foreach ($pvRecords as $pvRecord) {
            $pvRecord->update(['pv_count' => 0]);
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
        $date->subDay((config('constant.pv_aggregate_day') + 3));
        PvRecord::where('created_at', '<=', $date)->delete();

    }
}
