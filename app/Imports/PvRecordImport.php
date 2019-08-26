<?php

namespace App\Imports;

use App\PvRecord;
use Maatwebsite\Excel\Row;

class PvRecordImport extends ImportAbstract
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
        return PvRecord::class;
    }

    /**
     * @param Row $row
     * @return mixed
     * @throws \Exception
     */
    public function onRow(Row $row)
    {
        $row = $row->toArray();

        $model = new PvRecord([
            'hospital_id' => $this->getId('hospitals', $row['hospital_no']),
            'date_code' => 'aaaaaaaaa',
            'pv' => 1,
        ]);
        $model->save();
        $this->setId($model, $row);
    }
}
