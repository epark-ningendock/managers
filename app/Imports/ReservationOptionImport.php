<?php

namespace App\Imports;

use App\Option;
use App\Reservation;
use App\ReservationOption;
use Maatwebsite\Excel\Row;

class ReservationOptionImport extends ImportAbstract
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
        return ReservationOption::class;
    }

    /**
     * @param Row $row
     * @return mixed
     */
    public function onRow(Row $row)
    {
        $row = $row->toArray();

        $reservation = new Reservation([
            'hospital_id' => $this->getId('hospitals', $row['hospital_no']),
            'course_id' => 0,
            'channel' => $row['channel'],
            'reservation_status' => $row['reservation_tp'],
            'completed_date' => $row['appoint_date'],
            'cancel_date' => $row['cancel_dt'],
            'site_code' => $row['site_code'],
            'customer_id' => $this->getId('customers', $row['customer_id']),
            'member_number' => $row['member_no'],
            'is_repeat' => 0,
//            'is_representative' => $row['representative_fg'],
            'tax_included_price' => $row['course_price'],
//            'is_billable' => $row['billable_flg'],
            'reservation_date' => $row['appoint_date'],
            'claim_month' => $row['claim_month'],
            'is_payment' => 0,
            'reservation_memo' => $row['memo_appoint'],
            'fee_rate' => null,
            'fee' => null,
            'is_free_hp_link' => 0,
            'is_health_insurance' => 0,
        ]);
        $reservation->save();

        for ($i = 1; $i < 9; $i++) {
            if (is_null($row[sprintf('option_name_%d', $i)])) {
                break;
            }
            $option_id = Option::firstOrCreate([
                'hospital_id' => $this->getId('hospitals', $row['hospital_no']),
                'name' => $row[sprintf('option_name_%d', $i)],
                'price' => $row[sprintf('option_price_%d', $i)],
                'order' => $i,
            ])->id;

            ReservationOption::create([
                'reservation_id' => $reservation->id,
                'option_id' => $option_id,
                'option_price' => $row[sprintf('option_price_%d', $i)],
            ]);
        }
    }
}
