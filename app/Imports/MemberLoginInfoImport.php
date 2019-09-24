<?php

namespace App\Imports;

use App\MemberLoginInfo;
use Maatwebsite\Excel\Row;

class MemberLoginInfoImport extends ImportAbstract
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
        return MemberLoginInfo::class;
    }

    /**
     * @param Row $row
     * @return mixed
     * @throws \Exception
     */
    public function onRow(Row $row)
    {
        $row = $row->toArray();

        $model = new MemberLoginInfo([
            'epark_member_id' => $row['epark_member_id'],
            'mail_info_delivery' => $row['mail_info_delivery'],
            'nick_use' => $row['nick_use'],
            'contact' => $row['contact'],
            'contact_name' => $row['contact_name'],
            'status' => $row['status'],
        ]);

        $model->save();
        $this->deleteIf($model, $row, 'status', ['X']);
        $this->setId($model, $row);
    }
}
