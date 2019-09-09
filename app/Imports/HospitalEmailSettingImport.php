<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Row;

class HospitalEmailSettingImport extends ImportBAbstract implements WithEvents
{
    use RegistersEventListeners;

    private static $arr = [];

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
        return HospitalEmailSettingImport::class;
    }

    /**
     * @param Row $row
     * @return mixed
     */
    public function onRow(Row $row)
    {
        $row = $row->toArray();

        if ($this->getValue($row, 'IDENT_KEY') !== '0111') {
            return;
        }

        static::$arr['hospital_id'] = $this->getId('hospitals', $this->hospital_no);

        $key_code = $this->getValue($row, 'KEY_CODE');
        $value = $this->getValue($row, 'KEY_VALUE');

        switch ($key_code) {
            case '02':
                static::$arr['in_hospital_confirmation_email_reception_flg'] = $value;
                break;
            case '03':
                static::$arr['in_hospital_change_email_reception_flg'] = $value;
                break;
            case '04':
                static::$arr['in_hospital_cancellation_email_reception_flg'] = $value;
                break;
            case '05':
                static::$arr['email_reception_flg'] = $value;
                break;
            case '06':
            case '07':
//                static::$arr['reception_email1'] = $value;
//                static::$arr['reception_email2'] = $value;
//                static::$arr['reception_email3'] = $value;
//                static::$arr['reception_email4'] = $value;
                break;
            case '21':
                static::$arr['epark_in_hospital_reception_mail_flg'] = $value;
                break;
            case '22':
                static::$arr['epark_web_reception_email_flg'] = $value;
                break;
            default;
        }
    }

    public static function afterImport(AfterImport $event)
    {
        dd(static::$arr);
    }
}
