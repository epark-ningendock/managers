<?php

namespace App\Imports;

use App\ConvertedIdString;
use App\HospitalImage;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Row;

class HospitalImageImport extends ImportAbstract implements WithChunkReading
{
    /**
     * 旧システムのインポート対象テーブルのプライマリーキーを返す
     * Returns the primary key of the import target table of the old system
     * @return string
     */
    public function getOldPrimaryKeyName(): string
    {
        return 'file_no';
    }

    /**
     * 新システムの対象クラス名を返す
     * Returns the target class name of the new system
     * @return string
     */
    public function getNewClassName(): string
    {
        return HospitalImage::class;
    }

    /**
     * @param Row $row
     * @return mixed
     */
    public function onRow(Row $row)
    {
        $row = $row->toArray();
        $domain = config('services.imported_image_path');

        $hospital_id = $this->getId('hospitals', $row['hospital_no']);

        if (is_null($hospital_id)) {
            return;
        }

        $path = trim($domain, '/') . '/' . trim($row['path'], '/');

        $model = new HospitalImage([
            'hospital_id' => $hospital_id,
            'name' => $row['name'],
            'extension' => $row['extension'],
            'path' => $path. '/' . $row['name'] . '.' . $row['extension'],
            'memo1' => $row['memo1'],
            'memo2' => $row['memo2'],
            'is_display' => $row['flg_display'],
            'created_at' => $row['rgst'],
            'updated_at' => $row['updt'],
        ]);

        $model->save();
        $this->setId($model, $row);

        ConvertedIdString::firstOrCreate([
            'table_name' => 'hospital_images',
            'old_id' => $row['file_no'],
            'hospital_no' => $row['hospital_no'],
        ], [
            'table_name' => 'hospital_images',
            'old_id' => $row['file_no'],
            'new_id' => $model->id,
            'hospital_no' => $row['hospital_no'],
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
