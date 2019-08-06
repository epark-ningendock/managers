<?php

namespace App\Imports;

use App\ClassificationType;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;

class ClassificationTypeImport extends ImportAbstract implements ToModel
{
    /**
     * @param array $row
     *
     * @return Model|null
     */
    public function model(array $row)
    {
        return new ClassificationType([
            'name' => $row['name'],
            'order' => $row['order'],
            'status' => $row['status'],
            'is_editable' => $row['flg_edit'],
        ]);
    }
}
