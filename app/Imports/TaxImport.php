<?php

namespace App\Imports;

use App\TaxClass;
use Maatwebsite\Excel\Row;

class TaxImport extends ImportAbstract
{
    /**
     * 旧システムのインポート対象テーブルのプライマリーキーを返す
     * Returns the primary key of the import target table of the old system
     * @return string
     */
    public function getOldPrimaryKeyName(): string
    {
        return 'tax_class';
    }

    /**
     * 新システムの対象クラス名を返す
     * Returns the target class name of the new system
     * @return string
     */
    public function getNewClassName(): string
    {
        return TaxClass::class;
    }

    /**
     * @param Row $row
     * @return mixed
     * @throws \Exception
     */
    public function onRow(Row $row)
    {
        $row = $row->toArray();

        $model = new TaxClass([
            'name' => $row['tax_class_name'],
            'rate' => $row['tax_rate'],
            'life_time_from' => $row['life_time_from'],
            'life_time_to' => $row['life_time_to'],
        ]);

        $model->save();
        $this->setId($model, $row);
    }
}
