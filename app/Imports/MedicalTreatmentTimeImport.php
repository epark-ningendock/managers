<?php

namespace App\Imports;

use App\MedicalTreatmentTime;
use Maatwebsite\Excel\Row;

class MedicalTreatmentTimeImport extends ImportAbstract
{

    /**
     * 旧システムのインポート対象テーブルのプライマリーキーを返す
     * Returns the primary key of the import target table of the old system
     * @return string
     */
    public function getOldPrimaryKeyName(): string
    {
        return 'hospital_time_no';
    }

    /**
     * 新システムの対象クラス名を返す
     * Returns the target class name of the new system
     * @return string
     */
    public function getNewClassName(): string
    {
        return MedicalTreatmentTime::class;
    }

    /**
     * @param Row $row
     * @return mixed
     * @throws \Exception
     */
    public function onRow(Row $row)
    {
        $row = $row->toArray();

        $hospital_id= $this->getId('hospitals', $row['hospital_no']);

        if (is_null($hospital_id)) {
            return;
        }

        $model = new MedicalTreatmentTime([
            'hospital_id' => $hospital_id,
            'start' => $row['start'],
            'end' => $row['end'],
            'mon' => $row['mon'],
            'tue' => $row['tue'],
            'wed' => $row['wed'],
            'thu' => $row['thu'],
            'fri' => $row['fry'],
            'sat' => $row['sat'],
            'sun' => $row['sun'],
            'hol' => $row['hol'],
            'status' => $row['status'],
            'created_at' => $row['rgst'],
            'updated_at' => $row['updt'],
        ]);

        $model->save();
        $this->deleteIf($model, $row, 'status', ['X']);
        $this->setId($model, $row);
    }
}
