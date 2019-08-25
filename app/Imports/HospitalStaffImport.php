<?php

namespace App\Imports;

use App\Hospital;
use App\HospitalStaff;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Row;
use function now;

class HospitalStaffImport extends ImportBAbstract
{
    /**
     * @return array
     */
    public function getColumns(): array
    {
        return [
            'HOSPITAL_NO',
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
        return 'USER_NO';
    }

    /**
     * 新システムの対象クラス名を返す
     * Returns the target class name of the new system
     * @return string
     */
    public function getNewClassName(): string
    {
        return HospitalStaff::class;
    }

    /**
     * @param Row $row
     * @return mixed
     */
    public function onRow(Row $row)
    {
        $row = $row->toArray();

        try {
            $model = new HospitalStaff([
                'name' => $this->getValue($row, 'USER_NAME'),
                'email' => $this->getValue($row, 'EMAIL'),
                'login_id' => $this->getValue($row, 'UID'),
                'password' => Hash::make($this->getValue($row, 'PWD')),
                'remember_token' => null,
                'first_login_at' => now(),
                'created_at' => $this->getValue($row, 'CREATE_DATE'),
                'updated_at' => $this->getValue($row, 'MODIFY_DATE'),
                'reset_token_digest' => null,
                'reset_sent_at' => null,
                'hospital_id' => Hospital::withTrashed()->where('old_karada_dog_id', $this->hospital_no)->get()->first()->id,
            ]);
            $model->save();
            $this->setId($model, $row);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
