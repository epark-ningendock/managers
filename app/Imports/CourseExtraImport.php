<?php

namespace App\Imports;

use App\Course;
use Maatwebsite\Excel\Row;

class CourseExtraImport extends ImportBAbstract
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
        $course_id = $this->getValue($row, 'LINEGROUP_ID');

        $course = Course::find($course_id);
        if (is_null($course)) {
            return;
        }
        $course->update([
            'cancellation_deadline' => $this->getValue($row, 'LINEGROUP_CANCELLATION_DAYS'),
            'reception_start_date' => $this->getValue($row, 'LINEGROUP_END_DAYS'),
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
