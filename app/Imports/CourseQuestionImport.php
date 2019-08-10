<?php

namespace App\Imports;

use App\CourseQuestion;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Row;

class CourseQuestionImport extends ImportAbstract
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
        return CourseQuestion::class;
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
            $model = new CourseQuestion([
                'course_id' => $this->getId('courses', $row['course_no']),
                'question_number' => $row['flg_qa_no'],
                'is_question' => $row['flg_qa'],
                'question_title' => $row['flg_qa_title'],
                'answer01' => $row['flg_qa_answer01'],
                'answer02' => $row['flg_qa_answer02'],
                'answer03' => $row['flg_qa_answer03'],
                'answer04' => $row['flg_qa_answer04'],
                'answer05' => $row['flg_qa_answer05'],
                'answer06' => $row['flg_qa_answer06'],
                'answer07' => $row['flg_qa_answer07'],
                'answer08' => $row['flg_qa_answer08'],
                'answer09' => $row['flg_qa_answer09'],
                'answer10' => $row['flg_qa_answer10'],
                'created_at' => $row['rgst'],
                'updated_at' => $row['updt'],
            ]);
            $model->save();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
