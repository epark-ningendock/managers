<?php

namespace App\Imports;

use App\ConvertedId;
use App\ConvertedIdString;
use App\Course;
use App\CourseDetail;
use App\Hospital;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Row;

class CourseDetailImport extends ImportAbstract implements WithChunkReading
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
            $old_id = $row['course_no'];
            $hospital_id = $this->getId('hospitals', $row['hospital_no']);
            $old_hospital_id = Hospital::find($hospital_id)->old_karada_dog_id;
            $converted_idstring = ConvertedIdString::where('table_name', 'courses')
                ->where('old_id', intval($old_id))
                ->where('hospital_no', $old_hospital_id)
                ->first();

            if (!$converted_idstring) {
                return;
            } 
            $model = new CourseDetail([
                'course_id' => $converted_idstring->new_id,
                'major_classification_id' => $row['item_category_dai_no'],
                'middle_classification_id' => $row['item_category_chu_no'],
                'minor_classification_id' => $row['item_category_sho_no'],
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

    public function batchSize(): int
    {
        return 10000;
    }
    public function chunkSize(): int
    {
        return 10000;
    }
}
