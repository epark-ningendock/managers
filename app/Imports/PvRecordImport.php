<?php

namespace App\Imports;

use App\PvRecord;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Row;

class PvRecordImport extends ImportAbstract implements WithEvents
{
    use RegistersEventListeners;

    private static $count = [];

    public function __construct()
    {
        static::$count = null;
    }

    /**
     * 旧システムのインポート対象テーブルのプライマリーキーを返す
     * Returns the primary key of the import target table of the old system
     * @return string
     */
    public function getOldPrimaryKeyName(): string
    {
        return 'no';
    }

    /**
     * 新システムの対象クラス名を返す
     * Returns the target class name of the new system
     * @return string
     */
    public function getNewClassName(): string
    {
        return PvRecord::class;
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
        $date_code = Carbon::createFromFormat('Y-m-d H:i:s', $row['dt'])->format('Ymd');

        if (is_null($hospital_id)) {
            return;
        }

        if (!isset(static::$count[$hospital_id][$date_code])) {
            static::$count[$hospital_id][$date_code] = 0;
        }
        static::$count[$hospital_id][$date_code]++;
    }

    public static function afterImport(AfterImport $event)
    {
        foreach (static::$count as $hospital_id => $row) {
            foreach ($row as $dt => $count) {

                PvRecord::create([
                    'hospital_id' => $hospital_id,
                    'date_code' => $dt,
                    'pv' => $count,
                ]);
            }
        }
    }
}