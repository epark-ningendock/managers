<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Jobs\PvAggregateJob;

class PvAggregateCommand extends Command
{
    protected $signature = 'pv-aggregate
                            {--d|aggregate_day= : 集計日数}';
    protected $description = 'pv集計の更新ジョブを登録する。';

    /**
     * 指定日数のPV集計を実行するジョブを登録する
     *
     * @return バッチ実行成否
     */
    public function handle()
    {
        // 集計日数取得
        $aggregateDay = $this->option('aggregate_day');
        $deleteFlg = false;
        if (empty($aggregateDay)) {
            $aggregateDay = config('constant.pv_aggregate_day');
            $deleteFlg = true;
        }

        $date = Carbon::today();
        $date = $date->subDay($aggregateDay);

        // PV集計ジョブをキューに登録
        $this->queue($date, $deleteFlg);

    }

    public function queue(Carbon $aggregateDay, bool $deleteFlg)
    {
        $job = (new PvAggregateJob($aggregateDay, $deleteFlg))->onQueue('pv-aggregate');
        dispatch($job);
    }
}
