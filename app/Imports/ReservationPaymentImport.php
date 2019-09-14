<?php

namespace App\Imports;

use App\Reservation;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Row;

class ReservationPaymentImport extends ImportBAbstract
{
    /**
     * 旧システムのインポート対象テーブルのプライマリーキーを返す
     * Returns the primary key of the import target table of the old system
     * @return string
     */
    public function getOldPrimaryKeyName(): string
    {
        return '';
    }

    /**
     * 新システムの対象クラス名を返す
     * Returns the target class name of the new system
     * @return string
     */
    public function getNewClassName(): string
    {
        return Reservation::class;
    }

    /**
     * @param Row $row
     * @return mixed
     */
    public function onRow(Row $row)
    {
        $row = $row->toArray();

        $reservation = Reservation::find($this->getId('reservations', $this->getValue($row, 'APPOINT_ID')));

        if (is_null($reservation)) {
            return;
        }

        $arr = [
            'payment_status' => $this->getValue($row, 'PAYMENT_STATUS'),
            'trade_id' => $this->getValue($row, 'TRADE_ID'),
            'order_id' => $this->getValue($row, 'ORDER_ID'),
            'settlement_price' => $this->getValue($row, 'CARD_PAYMENT_AMOUNT'),
            'payment_method' => $this->getValue($row, 'PAYMENT_METHOD'),
            'cashpo_used_price' => $this->getValue($row, 'CASHPO_USED_AMOUNT'),
            'amount_unsettled' => $this->getValue($row, 'AMOUNT_UNSETTLED'),
        ];

        try {
            $model = new Reservation($arr);
            $model->save();

            $this->setId($model, $row);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
