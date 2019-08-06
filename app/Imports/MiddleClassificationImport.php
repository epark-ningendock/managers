<?php

namespace App\Imports;

use App\MiddleClassification;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;

class MiddleClassificationImport extends ImportAbstract implements ToModel
{
    /**
     * @param array $row
     *
     * @return Model|null
     */
    public function model(array $row)
    {
        return new MiddleClassification([
            'id' => $row['item_category_chu_no'],
            'major_classification_id' => $row['item_category_dai_no'],
            'name' => $row['name'],
            'status' => $row['status'],
            'order' => $row['order'],
            'is_icon' => $row['flg_icon'],
            'icon_name' => $row['icon_name'],
        ]);
    }
}
