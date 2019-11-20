<?php

namespace App\Imports;

use App\DistrictCode;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Row;

class DistrictCodeImport extends ImportAbstract implements WithChunkReading
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
        return DistrictCode::class;
    }

    /**
     * @param Row $row
     * @return mixed
     * @throws \Exception
     */
    public function onRow(Row $row)
    {
        $row = $row->toArray();

        $model = new DistrictCode([
            'district_code' => sprintf('%07d', $row['no']),
            'prefecture_id' => $row['pref'],
            'name' => $row['name'],
            'kana' => $row['kana'],
            'status' => $row['status'],
            'created_at' => $row['rgst'] ?? \now(),
            'updated_at' => $row['updt'] ?? \now(),
        ]);

        $model->save();
        $this->deleteIf($model, $row, 'status', ['X']);
    }

    public function batchSize(): int
    {
        return 10000;
    }
    public function chunkSize(): int
    {
        return 10000;
    }
}
