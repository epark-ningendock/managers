<?php

namespace App\Imports;

use App\Availabil;
use App\CalendarDay;
use App\ConvertedIdString;
use App\Enums\Status;
use App\Hospital;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Row;

class AvailabilityImport extends ImportAbstract implements WithChunkReading
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
        return CalendarDay::class;
    }

    /**
     * @param Row $row
     * @return mixed
     * @throws \Exception
     */
    public function onRow(Row $row)
    {
        $row = $row->toArray();

        if (empty( $row['line_id']) || $row['hospital_no'] < 2000) {
            return;
        }

        $hospital_id = $this->getId('hospitals', $row['hospital_no']);

        if (is_null($hospital_id)) {
            return;
        }

        $old_id = Hospital::find($hospital_id)->old_karada_dog_id;

        $deleted_at = null;
        if ($row['status'] == 'X') {
            $deleted_at = Carbon::today();
        }

//        $model = new Availabil([
//            'hospital_no' => $old_id,
//            'course_no' => $row['course_no'],
//            'reservation_dt' => $row['reservation_dt'],
//            'line_id' => $row['line_id'],
//            'appoint_number' => $row['appoint_number'],
//            'reservation_frames' => $row['reservation_frames'],
//            'created_at' => $row['rgst'],
//            'updated_at' => $row['updt'],
//            'deleted_at' => $deleted_at,
//        ]);
//        $model->save();

        $c = ConvertedIdString::where('table_name', 'calendars')
            ->where('old_id', $row['line_id'])
            ->where('hospital_no', $old_id)
            ->first();

        if (!$c) {
            return;
        }

        $date = Carbon::createFromFormat('Ymd', $row['reservation_dt'])->format('Y-m-d');
        $ca = CalendarDay::where('calendar_id', $c->new_id)
            ->where('date', $date)
            ->get();

        if ($ca) {
            return;
        }

        $model = new CalendarDay([
            'date' => $date,
            'is_holiday' => 0,  //
            'is_reservation_acceptance' => 1,
            'reservation_frames' => $row['reservation_frames'],
            'calendar_id' => $c->new_id,
            'reservation_count' => $row['appoint_number'],
            'status' => $row['status'],
            'created_at' => Carbon::today(),
            'updated_at' => Carbon::today(),
            'deleted_at' => $deleted_at
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
