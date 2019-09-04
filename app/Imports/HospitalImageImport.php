<?php

namespace App\Imports;

use App\HospitalImage;
use Maatwebsite\Excel\Row;

class HospitalImageImport extends ImportAbstract
{
    /**
     * 旧システムのインポート対象テーブルのプライマリーキーを返す
     * Returns the primary key of the import target table of the old system
     * @return string
     */
    public function getOldPrimaryKeyName(): string
    {
        return 'file_no';
    }

    /**
     * 新システムの対象クラス名を返す
     * Returns the target class name of the new system
     * @return string
     */
    public function getNewClassName(): string
    {
        return HospitalImage::class;
    }

    /**
     * @param Row $row
     * @return mixed
     */
    public function onRow(Row $row)
    {
        $row = $row->toArray();

        $model = new HospitalImage([
            'hospital_id' => $this->getId('hospitals', $row['hospital_no']),
            'name' => $row['name'],
            'extension' => $row['extension'],
            'path' => $row['path'],
            'memo1' => $row['memo1'],
            'memo2' => $row['memo2'],
            'is_display' => $row['flg_display'],
            'created_at' => $row['rgst'],
            'updated_at' => $row['updt'],
        ]);

        $model->save();
        $this->setId($model, $row);
    }
}
