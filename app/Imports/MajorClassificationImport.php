<?php

namespace App\Imports;

use App\MajorClassification;
use Maatwebsite\Excel\Concerns\ToModel;

class MajorClassificationImport extends ImportAbstract implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new MajorClassification([
            'classification_type_id' => $row['iten_type_no'],
            'name' => $row['name'],
            'status' => $row['status'],
            'order' => $row['order'],
            'is_icon' => $row['flg_icon'],
            'icon_name' => $row['icon_name'],
        ]);
    }
}
