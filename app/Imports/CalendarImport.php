<?php

namespace App\Imports;

use App\Calendar;
use Maatwebsite\Excel\Row;

class CalendarImport extends ImportBAbstract
{
    /**
     * 旧システムのインポート対象テーブルのプライマリーキーを返す
     * Returns the primary key of the import target table of the old system
     * @return string
     */
    public function getOldPrimaryKeyName(): string
    {
        return 'LINE_ID';
    }

    /**
     * 新システムの対象クラス名を返す
     * Returns the target class name of the new system
     * @return string
     */
    public function getNewClassName(): string
    {
        return Calendar::class;
    }

    /**
     * @param Row $row
     * @return mixed
     */
    public function onRow(Row $row)
    {
        $row = $row->toArray();

        $model = new Calendar([
            'name' => $this->getValue($row, 'LINE_NAME'),
            'is_calendar_display' => $this->getValue($row, 'TEMP_RECEPTION_FG'),
            'hospital_id' => $this->getId('hospitals', $this->hospital_no),
            'created_at' => $this->getValue($row, 'CREATE_DATE'),
            'updated_at' => $this->getValue($row, 'MODIFY_DATE'),
        ]);

        $model->save();
        $this->setId($model, $row);
    }
}
