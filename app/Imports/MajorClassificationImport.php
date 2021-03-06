<?php

namespace App\Imports;

use App\MajorClassification;
use Exception;
use Maatwebsite\Excel\Row;

class MajorClassificationImport extends ImportAbstract
{
    /**
     * 旧システムのインポート対象テーブルのプライマリーキーを返す
     * Returns the primary key of the import target table of the old system
     * @return string
     */
    public function getOldPrimaryKeyName(): string
    {
        return 'item_category_dai_no';
    }

    /**
     * 新システムの対象クラス名を返す
     * Returns the target class name of the new system
     * @return string
     */
    public function getNewClassName(): string
    {
        return MajorClassification::class;
    }

    /**
     * @param Row $row
     * @return mixed
     * @throws Exception
     */
    public function onRow(Row $row)
    {
        $row = $row->toArray();

        $model = new MajorClassification([
            'classification_type_id' => $this->getId('classification_types', $row['iten_type_no']),
            'name' => $row['name'],
            'status' => $row['status'],
            'order' => $row['order'],
            'is_icon' => $row['flg_icon'],
            'icon_name' => $row['icon_name'],
            'created_at' => $row['rgst'],
            'updated_at' => $row['updt'],
            'deleted_at' => ($row['status'] === 'X') ? now() : null,
        ]);
        $model->save();
        $this->deleteIf($model, $row, 'status', ['X']);
        $this->setId($model, $row);
    }
}
