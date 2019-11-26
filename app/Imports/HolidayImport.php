<?php

namespace App\Imports;

use App\Calendar;
use App\Holiday;
use App\Hospital;
use App\MonthlyWaku;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Row;

class HolidayImport extends ImportBAbstract implements WithChunkReading
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
                'hospital_id' => Hospital::withTrashed()->where('old_karada_dog_id', $this->hospital_no)->first()->id,
                'date' => $this->getValue($row, 'HOLIDAY'),
            ]);
            $model->save();

            $targets = Calendar::where('hospital_id', $model->hospital_id)->get();
            foreach ($targets as $calendar) {
                foreach ($calendar->calendar_days as $calendar_days) {
                    if ($calendar_days->date->eq(Carbon::create($model->date))) {
                        $calendar_days->is_holiday = 1;
                        $calendar_days->is_reservation_acceptance = 0;
                        $calendar_days->save();
                    }
                }
            }

        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
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
