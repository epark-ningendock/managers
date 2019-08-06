<?php

namespace App\Imports;

use App\Prefecture;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;

class PrefectureImport extends ImportAbstract implements ToModel
{
    /**
     * @param array $row
     *
     * @return Model|null
     */
    public function model(array $row)
    {
        return new Prefecture([
            'name' => $row['name'],
            'code' => $row['pref_no'],
        ]);
    }
}
