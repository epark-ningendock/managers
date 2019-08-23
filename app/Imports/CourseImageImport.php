<?php

namespace App\Imports;

use App\CourseImage;
use App\ImageOrder;
use Maatwebsite\Excel\Row;

class CourseImageImport extends ImportAbstract
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
        return CourseImage::class;
    }

    /**
     * @param Row $row
     * @return mixed
     * @throws \Exception
     */
    public function onRow(Row $row)
    {
        $row = $row->toArray();
        $model = new CourseImage([
            'course_id' => $this->getId('courses', $row['course_no']),
            'created_at' => $this->setCreatedAt($row['rgst']),
            'updated_at' => $this->setUpdatedAt($row['updt']),
        ]);
        $model->save();
    }
}
