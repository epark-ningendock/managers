<?php

namespace App\Imports;

use App\Station;
use Maatwebsite\Excel\Row;

class StationImport extends ImportAbstract
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
        return Station::class;
    }

    /**
     * @param Row $row
     * @return mixed
     * @throws \Exception
     */
    public function onRow(Row $row)
    {
        $row = $row->toArray();

        $model = new Station([
            'es_code' => $row['es_code'],
            'prefecture_id' => $this->getId('prefectures', $row['pref']),
            'name' => $row['name'],
            'kana' => $row['kana'],
            'longitude' => $row['x'],
            'latitude' => $row['y'],
            'status' => $row['status'],
            'created_at' => $row['rgst'],
            'updated_at' => $row['updt'],
        ]);
        $model->save();
        $this->deleteIf($model, $row, 'status', ['X']);
        $this->setId($model, $row);
    }
}
