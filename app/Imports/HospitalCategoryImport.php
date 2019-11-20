<?php

namespace App\Imports;

use App\HospitalCategory;
use App\ImageOrder;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Row;

class HospitalCategoryImport extends ImportAbstract implements WithChunkReading
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
        return HospitalCategory::class;
    }

    /**
     * @param Row $row
     * @return mixed
     * @throws \Exception
     */
    public function onRow(Row $row)
    {
        $row = $row->toArray();

        $hospital_id = $this->getId('hospitals', $row['hospital_no']);

        if (is_null($hospital_id)) {
            return;
        }
        $arr = [
            'hospital_id' => $hospital_id,
            'hospital_image_id' => $this->getId('hospital_images', $row['file_no']),
            'image_order' => $row['file_group_no'],
            'file_location_no' => $row['file_location_no'],
            'title' => $row['title'],
            'caption' => $row['caption'],
            'name' => $row['name'],
            'memo' => $row['memo'],
            'career' => $row['career'],
            'interview' => $row['interview'],
            'is_display' => $row['flg_display'],
            'order' => $row['order'],
            'order2' => $row['order2'],
            'created_at' => $row['rgst'],
            'updated_at' => $row['updt'],
        ];
        $model = new HospitalCategory($arr);
        $model->save();
        $this->deleteIf($model, $row, 'status', ['X']);

        if ((in_array($row['order'], [1, 2, 3, 4]))) {
            $model = new HospitalCategory($arr);
            $model->image_order = 9;
            $model->save();
        }

        $interview = $row['interview'];
        if (is_null($interview)) {
            return;
        }

        try {
            $xml = simplexml_load_string($interview);

            $h4 = [];
            $p = [];
            foreach ($xml->li as $element) {
                foreach ($element->h4 as $i => $elem) {
                    $h4[] = $elem->__toString();
                }
                foreach ($element->p as $i => $elem) {
                    $p[] = $elem->__toString();
                }
            }

            foreach ($h4 as $i => $val) {
                $model->interview_details()->create([
                    'question' => $val,
                    'answer' => $p[$i],
                    'order' => $i + 1,
                ]);
            }

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
