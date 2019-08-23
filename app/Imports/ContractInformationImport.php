<?php

namespace App\Imports;

use App\ContractInformation;
use Maatwebsite\Excel\Row;

class ContractInformationImport extends ImportBAbstract
{
    /**
     * @return array
     */
    public function getColumns(): array
    {
    }

    /**
     * 旧システムのインポート対象テーブルのプライマリーキーを返す
     * Returns the primary key of the import target table of the old system
     * @return string
     */
    public function getOldPrimaryKeyName(): string
    {
        // TODO: Implement getOldPrimaryKeyName() method.
    }

    /**
     * 新システムの対象クラス名を返す
     * Returns the target class name of the new system
     * @return string
     */
    public function getNewClassName(): string
    {
        return ContractInformation::class;
    }

    /**
     * @param Row $row
     * @return mixed
     */
    public function onRow(Row $row)
    {
        $row = $row->toArray();

        $model = new ContractInformation([

        ]);

        $model->save();
    }
}
