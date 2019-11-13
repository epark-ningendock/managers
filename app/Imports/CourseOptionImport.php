<?php

namespace App\Imports;

use App\ConvertedIdString;
use App\CourseOption;
use App\CourseQuestion;
use App\Enums\Status;
use App\Hospital;
use App\OldOption;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Row;

class CourseOptionImport extends ImportAbstract
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
        return CourseOption::class;
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
            $hospital_id = $this->getId('hospitals', $row['hospital_no']);
            $hospital = Hospital::find($hospital_id);
            $old_options = OldOption::where('hospital_no', $hospital->old_karada_dog_id)
                ->where('option_group_cd', $row['option_group_cd'])
                ->get();
            $converted_idstring = ConvertedIdString::where('table_name', 'courses')
                ->where('old_id', $row['course_no'])
                ->where('hospital_no', $hospital->old_karada_dog_id)
                ->first();

            if ($converted_idstring) {
                $course_id = $converted_idstring->new_id;
            } else {
                return;
            }
            foreach ($old_options as $option) {
                $model = new CourseOption([
                    'course_id' => $course_id,
                    'option_id' => $option->option_id,
                    'status' => Status::VALID,
                    'created_at' => $row['rgst'],
                    'updated_at' => $row['updt'],
                ]);
                $model->save();
            }

        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
