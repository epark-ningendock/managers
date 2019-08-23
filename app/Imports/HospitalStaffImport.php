<?php

namespace App\Imports;

use Maatwebsite\Excel\Row;

class HospitalStaffImport extends ImportBAbstract
{
    /**
     * @return array
     */
    public function getColumns(): array
    {
        return [
            'USER_NO',
            'UID',
            'NAME',
            'USER_ID',
            'SHOPOWNER_NO',
            'EMAIL',
            'AUTH_TP',
            'MEMO',
            'PWD',
            'LAST_PWD',
            'PWD_CHANGE_DATE',
            'UID_PWD',
            'UID_PWD_FAULT_COUNT',
            'UID_PWD_FAULT_DATE',
            'LAST_LOGIN_DATE',
            'PWD_LOCK_DATE',
            'PWD_RESET_COUNT',
            'PWD_LOCK_FG',
            'SECRET_QUST',
            'SECRET_QUST_ANSWER',
            'PWD_FAULT_COUNT',
            'PWD_FAULT_DATE',
            'SKIN_ID',
            'SKIN_NAME',
            'PWD_RESETED_DATE',
            'UID_USE_FLG',
            'CREATE_USER_NO',
            'CREATE_DATE',
            'MODIFY_USER_NO',
            'MODIFY_DATE',
            'DATA_VERSION',
        ];
    }

    /**
     * 旧システムのインポート対象テーブルのプライマリーキーを返す
     * Returns the primary key of the import target table of the old system
     * @return string
     */
    public function getOldPrimaryKeyName(): string
    {
        // TODO: Implement getOldPrimaryKeyName() method.
    }

    /**
     * 新システムの対象クラス名を返す
     * Returns the target class name of the new system
     * @return string
     */
    public function getNewClassName(): string
    {
        // TODO: Implement getNewClassName() method.
    }

    /**
     * @param Row $row
     * @return mixed
     */
    public function onRow(Row $row)
    {
        // TODO: Implement onRow() method.
    }
}
