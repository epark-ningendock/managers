<?php

namespace App\Imports;

use App\CalendarDay;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Row;

class CalendarDayImport extends ImportBAbstract
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
        return CalendarDay::class;
    }

    /**
     * @param Row $row
     * @return mixed
     */
    public function onRow(Row $row)
    {
        try {
            $row = $row->toArray();

            $model = new CalendarDay([
                'date' => \now()->format('Y-m-d'), //@todo
                'is_holiday' => 0,  // @todo
                'is_reservation_acceptance' => $this->getValue($row, 'OUTSIDE_RESERVATION'),
                'reservation_frames' => $this->getValue($row, 'RESERVATION_FRAMES'),
                'calendar_id' => $this->getId('calendars', $this->getValue($row, 'LINE_ID')),
                'created_at' => $this->getValue($row, 'CREATE_DATE'),
                'updated_at' => $this->getValue($row, 'MODIFY_DATE'),
            ]);
            $model->save();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
