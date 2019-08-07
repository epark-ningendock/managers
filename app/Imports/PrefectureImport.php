<?php

namespace App\Imports;

use App\Prefecture;
use Maatwebsite\Excel\Row;

class PrefectureImport extends ImportAbstract
{

    /**
     * 旧システムのインポート対象テーブルのプライマリーキーを返す
     * @return string
     */
    public function getOldPrimaryKeyName(): string
    {
        return 'pref_no';
    }

    /**
     * 新システムの対象クラス名を返す
     * @return string
     */
    public function getNewClassName(): string
    {
        return Prefecture::class;
    }

    /**
     * @param Row $row
     * @return mixed
     * @throws \Exception
     */
    public function onRow(Row $row)
    {
        $row = $row->toArray();

        $model = new Prefecture([
            'name' => $row['name'],
            'code' => $row['pref_no'],
            'created_at' => $row['rgst'],
            'updated_at' => $row['updt'],
        ]);

        $model->save();
        $this->deleteIf($model, $row, 'status', ['X']);
        $this->setId($model, $row);
    }
}
