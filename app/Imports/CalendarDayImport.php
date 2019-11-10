<?php

namespace App\Imports;

use App\CalendarDay;
use App\ConvertedIdString;
use App\Enums\Status;
use Carbon\Carbon;
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
            $c = ConvertedIdString::where('table_name', 'calendars')
                ->where('old_id', $row['line_id'])
                ->where('hospital_no', $this->hospital_no)
                ->first();

            $model = new CalendarDay([
                'date' => Carbon::create($row['date']),
                'is_holiday' => 0,  //
                'is_reservation_acceptance' => 1,
                'reservation_frames' => $row['frame'],
                'calendar_id' => $c->new_id,
                'status' => Status::VALID,
                'created_at' => Carbon::today(),
                'updated_at' => Carbon::today(),
            ]);
            $model->save();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
