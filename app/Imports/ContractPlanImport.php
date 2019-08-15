<?php

namespace App\Imports;

use App\ContractPlan;
use Maatwebsite\Excel\Row;

class ContractPlanImport extends ImportAbstract
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
        return ContractPlan::class;
    }

    /**
     * @param Row $row
     * @return mixed
     */
    public function onRow(Row $row)
    {
        // TODO: Implement onRow() method.
    }
}
