<?php

namespace App\Imports;

use App\Staff;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Row;

class StaffImport extends ImportAbstract
{
    /**
     * 旧システムのインポート対象テーブルのプライマリーキーを返す
     * Returns the primary key of the import target table of the old system
     * @return string
     */
    public function getOldPrimaryKeyName(): string
    {
        return 'no';
    }

    /**
     * 新システムの対象クラス名を返す
     * Returns the target class name of the new system
     * @return string
     */
    public function getNewClassName(): string
    {
        return Staff::class;
    }

    /**
     * @param Row $row
     * @return mixed
     * @throws \Exception
     */
    public function onRow(Row $row)
    {
        $row = $row->toArray();

        $model = new Staff([
            'name' => $row['name'],
            'login_id' => $row['id'],
            'password' => $row['passwd'],
            'authority' => '1',
            'status' => ($row['status'] == '1') ? 1 : 2,
            'email' => sprintf('dummy-%s@example.com', $row['id']),
            'remember_token' => Str::random(10),
            'first_login_at' => null,
            'created_at' => ($row['rgst'] == '0000-00-00 00:00:00') ? now() : $row['rgst'],
            'updated_at' => ($row['updt'] == '0000-00-00 00:00:00') ? now() : $row['updt'],
            'department_id' => null,
            'reset_token_digest' => null,
            'reset_sent_at' => null,
        ]);

        $model->save();

        $model->staff_auth()->create([
            'is_hospital' => ($row['is_hospital'] == 255) ? 3 : $row['is_hospital'],
            'is_staff' => ($row['is_staff'] == 255) ? 3 : $row['is_staff'],
            'is_cource_classification' => ($row['is_item_category'] == 255) ? 3 : $row['is_item_category'],
            'is_invoice' => ($row['is_invoice'] == 255) ? 7 : $row['is_invoice'],
            'is_pre_account' => '0',
            'is_contract' => '0',
            'staff_id' => $model->id,
            'created_at' => $row['rgst'],
            'updated_at' => $row['updt'],
            'deleted_at' => ($row['status'] == 'X') ? $row['updt'] : null,
        ]);

        $this->deleteIf($model, $row, 'status', ['X']);

        $this->setId($model, $row);
    }
}
