<?php

namespace App\Imports;

use App\HospitalCategory;
use App\ImageOrder;
use Maatwebsite\Excel\Row;

class HospitalCategoryImport extends ImportAbstract
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
        $model = new HospitalCategory([
            'hospital_id' => $this->getId('hospitals', $row['hospital_id']),

//            'hospital_image_id' => HospitalImage::where('hospital_id', $this->getId('hospitals', $row['hospital_id']))
//                ->where('file_no', $row['file_no'])->get()->id,

            'image_order' => ImageOrder::where('image_group_number', $row['file_group_no'])
                ->where('image_location_number', $row['file_location_no'])->get()->id,

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
        ]);
        $model->save();
        $this->deleteIf($model, $row, 'status', ['X']);
    }
}
