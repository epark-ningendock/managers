<?php

namespace App\Imports;

use App\Hospital;
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
        return 'reservation_id';
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
        $hospital_id = $this->getId('hospitals', $row['hospital_no']);

        $hospital = Hospital::find($hospital_id);

        if (is_null($hospital)) {
            return;
        }

        $hospital_plan = $hospital->hospital_plan;
        $hospital_plan->contractPlan->fee_rate;

        $reservation_id = $this->getIdForA('reservations',
            sprintf('%s_%s',
                $this->hospital_no,
                $this->getValue($row, 'APPOINT_ID')
            )
        );
        $reservation = Reservation::find($reservation_id);

        if (is_null($reservation)) {
            return;
        }

        $course_id = $reservation->course_id;

        $reservation = new Reservation([
            'hospital_id' => $hospital_id,
            'course_id' => $course_id,
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
            'reservation_date' => $row['reservation_dt'],
            'claim_month' => $row['claim_month'],
            'is_payment' => 0,
            'reservation_memo' => $row['memo_appoint'],
            'fee_rate' => Hospital::find($hospital_id)->hospital_plan->contractPlan->fee_rate,
            'fee' => null,
            'is_free_hp_link' => 0,
            'is_health_insurance' => 0,
        ]);
        $reservation->save();
        $this->setId($reservation, $row);

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
