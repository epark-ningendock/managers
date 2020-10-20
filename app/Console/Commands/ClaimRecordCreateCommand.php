<?php

namespace App\Console\Commands;

use App\Billing;
use App\Enums\BillingStatus;
use App\Hospital;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ClaimRecordCreateCommand extends Command
{
    protected $signature = 'claim-record-create';
    protected $description = '請求レコードを作成する';

    /**
     * 医療機関ごとの請求テーブルレコードを生成する
     *
     * @return バッチ実行成否
     */
    public function handle()
    {
        // 対象データ取得
        $hospitals = Hospital::all();

        // 請求月生成
        $billingMonth = $this->createBillingMonth();

        // 請求テーブル生成
        foreach ($hospitals as $hospital) {
            $billing = new Billing;
            $billing->hospital_id = $hospital->id;
            $billing->billing_month = $billingMonth;
            $billing->status = BillingStatus::UNCONFIRMED;
            $billing->save();
        }
    }

    /**
     * 請求月文字列を生成
     * @return string
     */
    protected function createBillingMonth() {
        $date = Carbon::today();
        $date->addMonth(1);
        return $date->year . sprintf('%02d', $date->month) ;
    }
}
