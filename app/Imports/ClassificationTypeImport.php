<?php

namespace App\Imports;

use App\ClassificationType;
use Maatwebsite\Excel\Row;

class ClassificationTypeImport extends ImportAbstract
{

    /**
     * 旧システムのインポート対象テーブルのプライマリーキーを返す
     * @return string
     */
    public function getOldPrimaryKeyName(): string
    {
        return 'iten_type_no';
    }

    /**
     * 新システムの対象クラス名を返す
     * @return string
     */
    public function getNewClassName(): string
    {
        return ClassificationType::class;
    }

    /**
     * @param Row $row
     * @return mixed
     * @throws \Exception
     */
    public function onRow(Row $row)
    {
        $row = $row->toArray();
        $model = ClassificationType::create([
            'name' => $row['name'],
            'order' => $row['order'],
            'status' => $row['status'],
            'is_editable' => $row['flg_edit'],
            'created_at' => $row['rgst'],
            'updated_at' => $row['updt'],
            'deleted_at' => ($row['status'] === 'X') ? now() : null,
        ]);
        $model->save();
        $this->deleteIf($model, $row, 'status', ['X']);
        $this->setId($model, $row);
    }
}
