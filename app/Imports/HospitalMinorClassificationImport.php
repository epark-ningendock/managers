<?php

namespace App\Imports;

use App\HospitalMinorClassification;
use Maatwebsite\Excel\Row;

class HospitalMinorClassificationImport extends ImportAbstract
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
        return HospitalMinorClassification::class;
    }

    /**
     * @param Row $row
     * @return mixed
     * @throws \Exception
     */
    public function onRow(Row $row)
    {
        $row = $row->toArray();
        $model = new HospitalMinorClassification([
            'middle_classification_id' => $this->getId('hospital_middle_classifications', $row['item_category_chu_no']),
            'name' => $row['name'],
            'status' => $row['status'],
            'is_fregist' => $row['flg_regist'],
            'order' => $row['order'],
            'is_icon' => $row['flg_icon'],
            'icon_name' => $row['icon_name'],
            'created_at' => $row['rgst'],
            'updated_at' => $row['updt'],
        ]);
        $model->save();
        $this->deleteIf($model, $row, 'status', ['X']);
        $this->setId($model, $row);
    }
}
