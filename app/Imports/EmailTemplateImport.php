<?php

namespace App\Imports;

use App\EmailTemplate;
use App\Hospital;
use Maatwebsite\Excel\Row;

class EmailTemplateImport extends ImportBAbstract
{
    /**
     * 旧システムのインポート対象テーブルのプライマリーキーを返す
     * Returns the primary key of the import target table of the old system
     * @return string
     */
    public function getOldPrimaryKeyName(): string
    {
        return 'SIMULTANEOUS_TRANSMIT_MAIL_TMPL_ID';
    }

    /**
     * 新システムの対象クラス名を返す
     * Returns the target class name of the new system
     * @return string
     */
    public function getNewClassName(): string
    {
        return EmailTemplate::class;
    }

    /**
     * @param Row $row
     * @return mixed
     */
    public function onRow(Row $row)
    {
        $row = $row->toArray();
        $model = new EmailTemplate([
            'hospital_id' => Hospital::withTrashed()->where('old_karada_dog_id', $this->hospital_no)->first()->id,
            'title' => $this->getValue($row, 'PC_TITLE'),
            'text' => $this->getValue($row, 'PC_BODY'),
        ]);
        $model->save();
        $this->setId($model, $row);
    }

    public function batchSize(): int
    {
        return 10000;
    }
    public function chunkSize(): int
    {
        return 10000;
    }
}
