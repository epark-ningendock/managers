<?php

namespace App\Imports;

use App\MinorClassification;
use Maatwebsite\Excel\Row;

class MinorClassificationImport extends ImportAbstract
{
    /**
     * 旧システムのインポート対象テーブルのプライマリーキーを返す
     * Returns the primary key of the import target table of the old system
     * @return string
     */
    public function getOldPrimaryKeyName(): string
    {
        return 'item_category_sho_no';
    }

    /**
     * 新システムの対象クラス名を返す
     * Returns the target class name of the new system
     * @return string
     */
    public function getNewClassName(): string
    {
        return MinorClassification::class;
    }

    /**
     * @param Row $row
     * @return mixed
     * @throws \Exception
     */
    public function onRow(Row $row)
    {
        $row = $row->toArray();

        $model = new MinorClassification([
            'major_classification_id' => $this->getId('major_classifications', $row['item_category_dai_no']),
            'middle_classification_id' => $this->getId('middle_classifications', $row['item_category_chu_no']),
            'name' => $row['name'],
            'is_fregist' => $row['flg_regist'],
            'status' => $row['status'],
            'order' => $row['order'],
            'max_length' => $row['max_length'],
            'is_icon' => $row['flg_icon'],
            'icon_name' => $row['icon_name'],
            'created_at' => $row['rgst'],
            'updated_at' => $row['updt'],
        ]);
        $model->save();
        if ($row['status'] === 'X') {
            $model->delete();
        }
        $this->setId($model, $row);
    }
}
