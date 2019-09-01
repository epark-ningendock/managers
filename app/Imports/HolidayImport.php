<?php

namespace App\Imports;

use App\Holiday;
use App\Hospital;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Row;

class HolidayImport extends ImportBAbstract
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
        return Holiday::class;
    }

    /**
     * @param Row $row
     * @return mixed
     */
    public function onRow(Row $row)
    {
        try {
            $row = $row->toArray();
            $model = new Holiday([
                'hospital_id' => Hospital::withTrashed()->where('old_karada_dog_id', $this->hospital_no)->get()->first()->id,
                'date' => $this->getValue($row, 'HOLIDAY'),
            ]);
            $model->save();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
