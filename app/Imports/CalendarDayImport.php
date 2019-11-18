<?php

namespace App\Imports;

use App\Availabil;
use App\CalendarDay;
use App\ConvertedIdString;
use App\Enums\Status;
use App\MonthlyWaku;
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

            $appoint_num = Availabil::where('hospital_no', $this->hospital_no)
                ->where('line_id',  $row['line_id'])
                ->where('reservation_dt')
                ->sum('appoint_number');

            if (empty($appoint_num)) {
                $appoint_num = 0;
            }

            $model = new CalendarDay([
                'date' => Carbon::create($row['date']),
                'is_holiday' => 0,  //
                'is_reservation_acceptance' => 1,
                'reservation_frames' => $row['frame'],
                'calendar_id' => $c->new_id,
                'reservation_count' => $appoint_num,
                'status' => Status::VALID,
                'created_at' => Carbon::today(),
                'updated_at' => Carbon::today(),
            ]);
            $model->save();

        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function batchSize(): int
    {
        return 100;
    }
    public function chunkSize(): int
    {
        return 100;
    }
}
