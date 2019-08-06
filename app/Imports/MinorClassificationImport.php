<?php

namespace App\Imports;

use App\MinorClassification;
use Maatwebsite\Excel\Concerns\ToModel;

class MinorClassificationImport extends ImportAbstract implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new MinorClassification([
            'id' => $row['item_category_sho_no'],
            'major_classification_id' => $row['item_category_dai_no'],
            'middle_classification_id' => $row['item_category_chu_no'],
            'name' => $row['name'],
            'is_fregist' => $row['flg_regist'],
            'status' => $row['status'],
            'order' => $row['order'],
            'max_length' => $row['max_length'],
            'is_icon' => $row['flg_icon'],
            'icon_name' => $row['icon_name'],
        ]);
    }
}
