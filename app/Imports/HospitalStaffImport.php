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
                'name' => $row['USER_NAME'],
                'email' => $row['EMAIL'],
                'login_id' => $row['UID'],
                'password' => Hash::make($row['PWD']),
                'remember_token' => null,
                'first_login_at' => now(),
                'created_at' => $row['CREATE_DATE'],
                'updated_at' => $row['MODIFY_DATE'],
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
