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
        // LINEGROUP_ID == old_course_id とみなす
        $old_course_id = $row['LINEGROUP_ID'];
        $course_id = $this->getIdForA('courses', $old_course_id);

        $course = Course::find($course_id);
        $course->update([
            'cancellation_deadline' => $row['LINEGROUP_CANCELLATION_DAYS'],
            'reception_start_date' => $row['LINEGROUP_START_DAYS'],
            'reception_end_date' => $row['LINEGROUP_END_DAYS'],
        ]);
    }
}
