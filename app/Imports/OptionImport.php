<?php

namespace App\Imports;

use App\Option;
use Maatwebsite\Excel\Row;

class OptionImport extends ImportAbstract
{
    /**
     * 旧システムのインポート対象テーブルのプライマリーキーを返す
     * Returns the primary key of the import target table of the old system
     * @return string
     */
    public function getOldPrimaryKeyName(): string
    {
        return 'option_cd';
    }

    /**
     * 新システムの対象クラス名を返す
     * Returns the target class name of the new system
     * @return string
     */
    public function getNewClassName(): string
    {
        return Option::class;
    }

    /**
     * @param Row $row
     * @return mixed
     * @throws \Exception
     */
    public function onRow(Row $row)
    {
        $row = $row->toArray();
        $model = new Option([
            'hospital_id' => $this->getId('hospitals', $row['hospital_no']),
            'name' => $row['name'],
            'confirm' => $row['confirm'],
            'price' => $row['price'],
            'tax_class_id' => null,  // @todo
            'order' => $row['order'],
            'status' => $row['status'],
            'deleted_at' => $row['deleted_at'],
            'created_at' => $row['created_at'],
            'updated_at' => $row['updated_at'],
            'lock_version' => $row['lock_version'],
        ]);
        $model->save();
        $this->deleteIf($model, $row, 'status', ['X']);
        $this->setId($model, $row);
    }
}
