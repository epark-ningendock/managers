<?php

namespace App\Imports;

use App\MailHistory;
use Maatwebsite\Excel\Row;

class MailHistoryImport extends ImportBAbstract
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
        return MailHistory::class;
    }

    /**
     * @param Row $row
     * @return mixed
     */
    public function onRow(Row $row)
    {
        $row = $row->toArray();

        $model = new MailHistory([
            'email' => $this->getValue($row, 'EMAIL'),
            'sent_datetime' => $this->getValue($row, 'SENT_DATETIME'),
            'sender_name' => $this->getValue($row, 'SENDER_NAME'),
            'sender_address' => $this->getValue($row, 'SENDER_ADDRESS'),
            'title' => $this->getValue($row, 'EMAIL_TITLE'),
            'contents' => $this->getValue($row, 'CONTENTS'),
            'customer_id' => null,
        ]);
        $model->save();
    }
}
