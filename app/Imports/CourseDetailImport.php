<?php

namespace App\Imports;

use App\CourseDetail;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Row;

class CourseDetailImport extends ImportAbstract
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
        return CourseDetail::class;
    }

    /**
     * @param Row $row
     * @return mixed
     * @throws \Exception
     */
    public function onRow(Row $row)
    {
        try {
            $row = $row->toArray();
            $model = new CourseDetail([
                'course_id' => $this->getId('courses', $row['course_no']),
                'major_classification_id' => $this->getId('major_classifications', $row['item_category_dai_no']),
                'middle_classification_id' => $this->getId('middle_classifications', $row['item_category_chu_no']),
                'minor_classification_id' => $this->getId('minor_classifications', $row['item_category_sho_no']),
                'select_status' => $row['select_status'],
                'inputstring' => $row['inputstring'],
                'created_at' => $row['rgst'],
                'updated_at' => $row['updt'],
            ]);
            $model->save();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
