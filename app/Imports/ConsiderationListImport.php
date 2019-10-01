<?php

namespace App\Imports;

use App\ConsiderationList;
use Maatwebsite\Excel\Row;

class ConsiderationListImport extends ImportAbstract
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
        return ConsiderationList::class;
    }

    /**
     * @param Row $row
     * @return mixed
     * @throws \Exception
     */
    public function onRow(Row $row)
    {
        $row = $row->toArray();

        $model = new ConsiderationList([
            'epark_member_id' => $row['epark_member_id'],
            'hospital_id' => $this->getId('hospitals', $row['hospital_no']),
            'course_id' => $this->getId('courses', $row['course_no']),
            'display_kbn' => $row['flg_display'],
            'status' => $row['status'],
        ]);

        $model->save();
    }
}
