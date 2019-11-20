<?php

namespace App\Imports;

use App\Hospital;
use App\OldOption;
use App\Option;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Row;

class OptionImport extends ImportAbstract implements WithChunkReading
{
    /**
     * 旧システムのインポート対象テーブルのプライマリーキーを返す
     * Returns the primary key of the import target table of the old system
     * @return string
     */
    public function getOldPrimaryKeyName(): string
    {
        return 'option_cd';
    }

    /**
     * 新システムの対象クラス名を返す
     * Returns the target class name of the new system
     * @return string
     */
    public function getNewClassName(): string
    {
        return Option::class;
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

        $deleted_at = null;
        if ($row['status'] == 'X') {
            $deleted_at = Carbon::today();
        }

        $model = new Option([
            'hospital_id' => $hospital_id,
            'name' => $row['option_name'] ?? '----',
            'confirm' => $row['confirm'],
            'price' => $row['price'],
            'tax_class_id' => $this->getId('tax_classes', $row['tax_class']),
            'order' => $row['order'],
            'status' => $row['status'],
            'created_at' => $row['rgst'],
            'updated_at' => $row['updt'],
            'deleted_at' => $deleted_at,
            'lock_version' => 1, //default
        ]);
        $model->save();
        $this->deleteIf($model, $row, 'status', ['X']);
        $this->setId($model, null, $row['option_cd'], $row['option_group_cd']);

        $hospital = Hospital::find($hospital_id);
        $old_model = new OldOption([
            'hospital_no' => $hospital->old_karada_dog_id,
            'option_group_cd' => $row['option_group_cd'],
            'option_cd' => $row['option_cd'],
            'option_id' => $model->id,
            'deleted_at' => $deleted_at,
        ]);
        $old_model->save();
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
