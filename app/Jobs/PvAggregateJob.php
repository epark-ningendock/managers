<?php

namespace App\Jobs;

use App\Hospital;
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
        $this->updatePvCount();

        // 過のpvデータを削除する
        $this->deletePvData();

    }

    /**
     * PV数をリセットする
     */
    protected function resetPvCount() {
        $hospitals = Hospital::all();
        foreach ($hospitals as $hospital) {
            $hospital->update(['pv_count' => 0]);
        }
    }

    /**
     * 医療機関のPV数を更新する
     * @param
     */
    protected function updatePvCount() {
        $hospitals = Hospital::all();
        foreach ($hospitals as $hospital) {
            $pv_count = PvRecord::where('hospital_id', $hospital->id)->sum('pv');
            $hospital->update(['pv_count' => $pv_count]);
        }
    }

    /**
     * PVデータを削除する
     */
    protected function deletePvData() {
        $date = Carbon::today();
        $date->subDay(config('constant.pv_aggregate_day'));
        PvRecord::where('created_at', '<=', $date)->delete();

    }
}
