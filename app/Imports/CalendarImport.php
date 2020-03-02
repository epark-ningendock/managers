<?php

namespace App\Imports;

use App\Calendar;
use App\ConvertedIdString;
use App\Course;
use App\Hospital;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Row;

class CalendarImport extends ImportBAbstract implements WithChunkReading
{
    /**
     * 旧システムのインポート対象テーブルのプライマリーキーを返す
     * Returns the primary key of the import target table of the old system
     * @return string
     */
    public function getOldPrimaryKeyName(): string
    {
        return 'LINE_ID';
    }

    /**
     * 新システムの対象クラス名を返す
     * Returns the target class name of the new system
     * @return string
     */
    public function getNewClassName(): string
    {
        return Calendar::class;
    }

    /**
     * @param Row $row
     * @return mixed
     */
    public function onRow(Row $row)
    {
        try {
            $row = $row->toArray();

            $con = ConvertedIdString::where('table_name', 'hospitals')
                ->where('old_id', $this->hospital_no)
                ->first();

//            $hospital = Hospital::where('old_karada_dog_id', $this->hospital_no)->first();

            $model = new Calendar([
                'name' => $this->getValue($row, 'LINE_NAME'),
                'is_calendar_display' => $this->getValue($row, 'TEMP_RECEPTION_FG'),
                'hospital_id' => $con->new_id,
//                'created_at' => $this->getValue($row, 'CREATE_DATE'),
//                'updated_at' => $this->getValue($row, 'MODIFY_DATE'),
            ]);

            $model->save();

            $c = ConvertedIdString::where('table_name', 'calendars')
                ->where('old_id', $this->getValue($row, 'LINE_ID'))
                ->where('hospital_no', $this->hospital_no)
                ->first();

            $courses = Course::where('hospital_id', $con->new_id)
                ->where('calendar_id', $this->getValue($row, 'LINE_ID'))
                ->get();

            foreach ($courses as $course) {
                $course->calendar_id = $c->new_id;
                $course->save();
            }

            $this->setId($model, $row);
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
