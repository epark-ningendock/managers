<?php

namespace App\Imports;

use App\Customer;
use Maatwebsite\Excel\Row;

class CustomerImport extends ImportBAbstract
{
    /**
     * 旧システムのインポート対象テーブルのプライマリーキーを返す
     * Returns the primary key of the import target table of the old system
     * @return string
     */
    public function getOldPrimaryKeyName(): string
    {
        return 'CUSTOMER_ID';
    }

    /**
     * 新システムの対象クラス名を返す
     * Returns the target class name of the new system
     * @return string
     */
    public function getNewClassName(): string
    {
        return Customer::class;
    }

    /**
     * @param Row $row
     * @return mixed
     * @todo ハッシュだと復号できないがそれでも良いのか？
     */
    public function onRow(Row $row)
    {
        $row = $row->toArray();
        $model = new Customer([
            'parent_customer_id' => $this->getValue($row, 'PARENT_CUSTOMER_ID'),
            'member_number' => $this->getValue($row, 'MEMBER_NO'),
            'registration_card_number' => $this->getValue($row, 'EXAMINATION_NO'),
            'family_name' => $this->getValue($row, 'LAST_NAME'), //@todo ハッシュ方法不明
            'first_name' => $this->getValue($row, 'FIRST_NAME'), //@todo ハッシュ方法不明
            'first_name_kana' => $this->getValue($row, 'FIRST_NAME_KANA'), //@todo ハッシュ方法不明
            'family_name_kana' => $this->getValue($row, 'LAST_NAME_KANA'), //@todo ハッシュ方法不明
            'tel' => $this->getValue($row, 'TEL_NO'), //@todo ハッシュ方法不明
            'email' => $this->getValue($row, 'EMAIL'),
            'postcode' => $this->getValue($row, 'POSTCODE'),
            'prefecture_id' => $this->getValue($row, 'PROV_CODE'),
            'address1' => $this->getValue($row, 'CITY'),
            'address2' => $this->getValue($row, 'BUILDING_NAME'), //@todo ハッシュ方法不明
            'sex' => $this->getValue($row, 'SEX'),
            'birthday' => $this->getValue($row, 'BIRTHDAY'),
            'memo' => $this->getValue($row, 'MEMO'),
            'claim_count' => $this->getValue($row, 'CLAIM_COUNT'),
            'recall_count' => $this->getValue($row, 'RECALL_COUNT'),
            'hospital_id' => $this->getId('hospitals', $this->hospital_no),
        ]);
        $model->save();
        $this->setId($model, $row);
    }
}
