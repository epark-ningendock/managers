<?php

namespace App\Imports;

use App\ContractInformation;
use App\HospitalStaff;
use Maatwebsite\Excel\Row;

class ContractInformationImport extends ImportBAbstract
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
        return ContractInformation::class;
    }

    /**
     * @param Row $row
     * @return mixed
     */
    public function onRow(Row $row)
    {
        $row = $row->toArray();

        $model = new ContractInformation([
            'id' => null, // @todo,
            'contractor_name_kana' => $row['SHOPOWNER_NAME_KANA'],
            'contractor_name' => $row['SHOPOWNER_NAME'],
            'application_date' => $row['NEW_APPLY_DATE'],
            'billing_start_date' => $row['SPECIFICFEE_START_DATE'],
            'cancellation_date' => $row['TERM_DATE'],
            'representative_name_kana' => $row['DELEGATE_NAME_KANA'],
            'representative_name' => $row['DELEGATE_NAME'],
            'postcode' => $row['POSTCODE'],
            'address' => null, // @todo 文字列で都道府県から接続？？？
            'tel' => $row['TEL'],
            'fax' => $row['FAX'],
            'karada_dog_id' => $this->hospital_no, // @todo 確認
            'code' => null, // @todo 対応するデータ不明
            'old_karada_dog_id' => $this->hospital_no, // @todo 確認
            'hospital_staff_id' => $this->getStaffIdByName($row['DELEGATE_NAME']), // @todo 確認
            'created_at' => $row['CREATE_DATE'],
            'updated_at' => $row['MODIFY_DATE'],
        ]);

        $model->save();
    }

    private function getStaffIdByName($name)
    {
        $staff = HospitalStaff::where('name', $name)->get()->first();
        if ($staff) {
            return $staff->id;
        }
        return null;
    }
}
