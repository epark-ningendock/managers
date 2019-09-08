<?php

namespace App\Imports;

use App\Reservation;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Row;

class ReservationImport extends ImportBAbstract implements WithEvents
{
    use RegistersEventListeners;

    /**
     * 旧システムのインポート対象テーブルのプライマリーキーを返す
     * Returns the primary key of the import target table of the old system
     * @return string
     */
    public function getOldPrimaryKeyName(): string
    {
        return 'APPOINT_ID';
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

//        $model = new Reservation([
//            'hospital_id' => Hospital::withTrashed()->where('old_karada_dog_id', $this->hospital_no)->get()->first()->id,
//            'course_id' => $this->getValue($row, 'LINE_ID'),
//            'reservation_date' => $this->getValue($row, 'APPOINT_DATE'),
//            'start_time_hour' => $this->getValue($row, 'START_TIME HH'),
//            'start_time_min' => $this->getValue($row, 'START_TIME MM'),
//            'end_time_hour' => $this->getValue($row, 'END_TIME HH'),
//            'end_time_min' => $this->getValue($row, 'END_TIME MM'),
//            'channel' => $this->getValue($row, 'TERMINAL_TP'),
//            'reservation_status' => $this->getValue($row, 'APPOINT_KBN'),
////            'completed_date' => $this->getValue($row, ''),
////            'cancel_date' => $this->getValue($row, ''),
////            'user_message' => $this->getValue($row, ''),
////            'site_code' => $this->getValue($row, ''),
//            'customer_id' => $this->getValue($row, 'CUSTOMER_ID'),
//            'epark_member_id' => $this->getValue($row, 'EPARK_MEMBER_ID'),
//            'member_number' => $this->getValue($row, 'MEMBER_NO'),
//            'terminal_type' => $this->getValue($row, 'TERMINAL_TP'),
//            'time_selected' => $this->getValue($row, 'RESERVATION_METHOD'),
//            'is_repeat' => $this->getValue($row, 'VISIT_HISTORY_FG'),
//            'is_representative' => $this->getValue($row, 'REPRESENTATIVE_FG'),
//            'tax_included_price' => $this->getValue($row, 'COURSE_PRICE_TAX'),
//            'adjustment_price' => $this->getValue($row, 'ADJUSTMENTS'),
////            'tax_rate' => $this->getValue($row, ''),
//            'second_date' => $this->getValue($row, 'SECOND_DATE'),
//            'third_date' => $this->getValue($row, 'THIRD_DATE'),
//            'is_choose' => $this->getValue($row, 'CHOOSE_FG'),
//            'campaign_code' => $this->getValue($row, 'CAMPAIGN_CD'),
//            'tel_timezone' => $this->getValue($row, 'TEL_TIMEZONE'),
////            'insurance_assoc_id' => $this->getValue($row, ''),
//            'insurance_assoc' => $this->getValue($row, 'INSURANCE_ASSOC'),
////            'mail_type' => $this->getValue($row, ''),
////            'cancelled_appoint_code' => $this->getValue($row, ''),
////            'is_billable' => $this->getValue($row, ''),
////            'claim_month' => $this->getValue($row, ''),
//            'is_payment' => $this->getValue($row, 'PAYMENT_FLG'),
//            'payment_status' => $this->getValue($row, 'PAYMENT_STATUS'),
//            'trade_id' => $this->getValue($row, 'TRADE_ID'),
//            'order_id' => $this->getValue($row, 'ORDER_ID'),
//            'settlement_price' => $this->getValue($row, 'CARD_PAYMENT_AMOUNT'),
//            'payment_method' => $this->getValue($row, 'PAYMENT_METHOD'),
//            'cashpo_used_price' => $this->getValue($row, 'CASHPO_USED_AMOUNT'),
//            'amount_unsettled' => $this->getValue($row, 'AMOUNT_UNSETTLED'),
////            'reservation_memo' => $this->getValue($row, ''),
////            'todays_memo' => $this->getValue($row, ''),
////            'internal_memo' => $this->getValue($row, ''),
////            'acceptance_number' => $this->getValue($row, ''),
//            'y_uid' => $this->getValue($row, 'Y_UID'),
////            'status' => $this->getValue($row, ''),
////            'lock_version' => $this->getValue($row, ''),
////            'applicant_name' => $this->getValue($row, ''),
////            'applicant_name_kana' => $this->getValue($row, ''),
////            'applicant_tel' => $this->getValue($row, ''),
////            'fee_rate' => $this->getValue($row, ''),
////            'fee' => $this->getValue($row, ''),
////            'is_free_hp_link' => $this->getValue($row, ''),
////            'is_health_insurance' => $this->getValue($row, ''),
//        ]);
//
//        $model->save();
//        $this->setId($model, $row);
    }

    /**
     * @param BeforeImport $event
     */
    public static function beforeImport(BeforeImport $event)
    {
        $file = static::$realpath;
        $dir = dirname($file);

        $appoints_detail_csv = $dir . DIRECTORY_SEPARATOR . 'T_OP_T_HYB_APPOINT_DETAIL.csv';
        if (!file_exists($appoints_detail_csv)) {
            return;
        }
        $fp = new \SplFileObject($appoints_detail_csv);
        $fp->setFlags(\SplFileObject::READ_CSV);
        $fp->setCsvControl(',', '"', '\\');

        $headers = [];
        $rows = [];
        foreach ($fp as $i => $line) {
            if ($i == 0) {
                $headers = $line;
                continue;
            }
            if (count($headers) != count($line)) {
                Log::error('Column number mismatch! line:' . ($i + 1), $line);

                continue;
            }
            $rows[] = array_combine($headers, $line);
        }
        dd($rows);
    }
}
