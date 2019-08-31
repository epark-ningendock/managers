<?php

namespace App\Imports;

use App\Rail;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Row;

class RailStationImport extends ImportAbstract
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
        return '';
    }

    /**
     * @param Row $row
     * @return mixed
     * @throws \Exception
     */
    public function onRow(Row $row)
    {
        try {
            $row = $row->toArray();

            if ($row['status'] == 'X') {
                return;
            }

            Rail::find($this->getId('rails', $row['rail']))
                ->stations()
                ->attach(
                    $this->getId('stations', $row['station']),
                    [
                        'created_at' => $row['rgst'],
                        'updated_at' => $row['updt'],
                    ]
                );

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            dd($e->getMessage());
        }
    }
}
