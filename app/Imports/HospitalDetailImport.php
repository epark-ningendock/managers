<?php

namespace App\Imports;

use App\HospitalDetail;
use Maatwebsite\Excel\Row;

class HospitalDetailImport extends ImportAbstract
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
        return HospitalDetail::class;
    }

    /**
     * @param Row $row
     * @return mixed
     * @throws \Exception
     */
    public function onRow(Row $row)
    {
        $row = $row->toArray();

        $model = new HospitalDetail([
            'hospital_id' => $this->getId('hospitals', $row['hospital_no']),
            'minor_classification_id' => $this->getId('minor_classifications', $row['item_category_sho_no']),
            'select_status' => $row['select_status'],
            'inputstring' => $row['inputstring'],
            'created_at' => $row['rgst'],
            'updated_at' => $row['updt'],
        ]);

        $model->save();
    }
}