<?php

namespace App\Imports;

use App\Availabil;
use App\Hospital;
use Carbon\Carbon;
use Maatwebsite\Excel\Row;

class AvailabilityImport extends ImportAbstract
{
    /**
     * 旧システムのインポート対象テーブルのプライマリーキーを返す
     * Returns the primary key of the import target table of the old system
     * @return string
     */
    public function getOldPrimaryKeyName(): string
    {
        return 'hospital_no';
    }

    /**
     * 新システムの対象クラス名を返す
     * Returns the target class name of the new system
     * @return string
     */
    public function getNewClassName(): string
    {
        return Availabil::class;
    }

    /**
     * @param Row $row
     * @return mixed
     * @throws \Exception
     */
    public function onRow(Row $row)
    {
        $row = $row->toArray();

        $hospital_id = $this->getId('hospitals', $row['hospital_no']);

        if (is_null($hospital_id)) {
            return;
        }

        $old_id = Hospital::find($hospital_id)->old_karada_dog_id;

        $deleted_at = null;
        if ($row['status'] == 'X') {
            $deleted_at = Carbon::today();
        }

        $model = new Availabil([
            'hospital_no' => $old_id,
            'course_no' => $row['course_no'],
            'reservation_dt' => $row['reservation_dt'],
            'line_id' => $row['line_id'],
            'appoint_number' => $row['appoint_number'],
            'reservation_frames' => $row['reservation_frames'],
            'created_at' => $row['rgst'],
            'updated_at' => $row['updt'],
            'deleted_at' => $deleted_at,
        ]);
        $model->save();
    }

    public function batchSize(): int
   {
       return 10000;
   }
   public function chunkSize(): int
   {
       return 10000;
   }
}
