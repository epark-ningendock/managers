<?php

namespace App\Imports;

use App\ConvertedId;
use App\ConvertedIdString;
use App\CourseImage;
use App\Hospital;
use App\HospitalImage;
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
        $converted_idst = ConvertedIdString::where('table_name', 'hospital_images')
            ->where('old_id', $row['file_no'])
            ->where('hospital_no', $row['hospital_no'])
            ->first();

        if (!$converted_idst) {
            return;
        }
        $hospital_image = HospitalImage::find($converted_idst->new_id);
        $name = null;
        $extension = null;
        $path = null;
        if ($hospital_image) {
            $name = $hospital_image->name;
            $extension = $hospital_image->extension;
            $path = $hospital_image->path;
        }

        $c = ConvertedId::where('table_name', 'hospitals')
            ->where('old_id', $row['hospital_no'])
            ->first();

        if (!$c) {
            return;
        }

        $new_hospital_id = $c->new_id;
        $hospital = Hospital::find($new_hospital_id);

        $converted_idstring = ConvertedIdString::where('table_name', 'courses')
            ->where('old_id', $row['course_no'])
            ->where('hospital_no', $hospital->old_karada_dog_id)
            ->first();

        if ($converted_idstring) {
            $course_id = $converted_idstring->new_id;
        } else {
            return;
        }
        $model = new CourseImage([
            'course_id' => $course_id,
            'name' => $name,
            'extension' => $extension,
            'path' => $path,
            'created_at' => $this->setCreatedAt($row['rgst']),
            'updated_at' => $this->setUpdatedAt($row['updt']),
        ]);
        $model->save();
    }
}
