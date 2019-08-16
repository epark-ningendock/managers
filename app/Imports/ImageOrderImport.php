<?php

namespace App\Imports;

use App\ImageOrder;
use Maatwebsite\Excel\Row;

class ImageOrderImport extends ImportAbstract
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
        return ImageOrder::class;
    }

    /**
     * @param Row $row
     * @return mixed
     * @throws \Exception
     */
    public function onRow(Row $row)
    {
        $row = $row->toArray();

        $model = new ImageOrder([
            'image_group_number' => $row['file_group_no'],
            'image_location_number' => $row['file_location_no'],
            'name' => $row['name'],
            'order' => $row['order'],
            'status' => $row['status'],
            'created_at' => $this->setCreatedAt($row['rgst']),
            'updated_at' => $this->setUpdatedAt($row['updt']),
        ]);
        $model->save();
        $this->deleteIf($model, $row, 'status', ['X']);
    }
}
