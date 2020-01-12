<?php

namespace App\Imports;

use App\ConvertedIdString;
use App\Course;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Row;

class CourseExtraImport extends ImportBAbstract implements WithChunkReading
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
        return Course::class;
    }

    /**
     * @param Row $row
     * @return mixed
     */
    public function onRow(Row $row)
    {
        $row = $row->toArray();

        // LINEGROUP_ID == old_course_id とみなす
        $old_course_id = $this->getValue($row, 'LINEGROUP_ID');
        $course_id = ConvertedIdString::where('table_name', 'courses')
            ->where('old_id', $old_course_id)
            ->where('hospital_no', $this->hospital_no)
            ->first()->new_id;

        $course = Course::find($course_id);
        if (is_null($course)) {
            return;
        }
        $start_date = $this->getValue($row, 'LINEGROUP_END_DAYS');
        if (isset($start_date) && strlen($start_date) == 1) {
            $start_date = $start_date + 1;
        }

        $course->update([
            'cancellation_deadline' => $this->getValue($row, 'LINEGROUP_CANCELLATION_DAYS'),
            'reception_start_date' => $start_date,
            'reception_end_date' => $this->getValue($row, 'LINEGROUP_START_DAYS'),
        ]);
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
