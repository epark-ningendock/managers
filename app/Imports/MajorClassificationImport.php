<?php

namespace App\Imports;

use App\MajorClassification;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;

class MajorClassificationImport extends ImportAbstract implements ToModel
{
    /**
     * @param array $row
     *
     * @return Model|null
     */
    public function model(array $row)
    {
        return new MajorClassification([
            'id' => $row['item_category_dai_no'],
            'classification_type_id' => $row['iten_type_no'],
            'name' => $row['name'],
            'status' => $row['status'],
            'order' => $row['order'],
            'is_icon' => $row['flg_icon'],
            'icon_name' => $row['icon_name'],
        ]);
    }
}
