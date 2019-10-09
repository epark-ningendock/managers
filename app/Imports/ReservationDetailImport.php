<?php

namespace App\Imports;

use App\Reservation;
use Maatwebsite\Excel\Row;

class ReservationDetailImport extends ImportBAbstract
{

    /**
     * 旧システムのインポート対象テーブルのプライマリーキーを返す
     * Returns the primary key of the import target table of the old system
     * @return string
     */
    public function getOldPrimaryKeyName(): string
    {
        return 'ID';
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

        $reservation = Reservation::findOrCreate($this->getId('reservations', $this->getValue($row, 'APPOINT_ID')));

        if (is_null($reservation)) {
            return;
        }

        $reservation->fill([
            'channel' => $this->getValue($row, 'TERMINAL_TP'),
            'reservation_status' => $this->getValue($row, 'APPOINT_KBN'),
            'customer_id' => $this->getId('customers', $this->getValue($row, 'CUSTOMER_ID')),
            'epark_member_id' => $this->getValue($row, 'EPARK_MEMBER_ID'),
            'member_number' => $this->getValue($row, 'MEMBER_NO'),
            'terminal_type' => $this->getValue($row, 'TERMINAL_TP'),
            'tax_included_price' => $this->getValue($row, 'COURSE_PRICE_TAX'),
            'adjustment_price' => $this->getValue($row, 'ADJUSTMENTS'),
            'second_date' => $this->getValue($row, 'SECOND_DATE'),
            'third_date' => $this->getValue($row, 'THIRD_DATE'),
            'is_choose' => $this->getValue($row, 'CHOOSE_FG'),
            'campaign_code' => $this->getValue($row, 'CAMPAIGN_CD'),
            'tel_timezone' => $this->getValue($row, 'TEL_TIMEZONE'),
            'insurance_assoc' => $this->getValue($row, 'INSURANCE_ASSOC'),
            'is_payment' => $this->getValue($row, 'PAYMENT_FLG'),
            'y_uid' => $this->getValue($row, 'Y_UID'),
            'applicant_name' => $this->getValue($row, 'LAST_NAME') . $this->getValue($row, 'FIRST_NAME'),
            'applicant_name_kana' => $this->getValue($row, 'LAST_NAME_KANA') . $this->getValue($row, 'FIRST_NAME_KANA'),
            'applicant_tel' => substr(str_replace('-', '', $this->getValue($row, 'TEL_NO')), 0, 11),
        ]);

        $answers = $reservation->reservation_answers;
        dd($answers);

        $reservation->save();
    }
}
