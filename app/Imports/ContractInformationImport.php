<?php

namespace App\Imports;

use App\ContractInformation;
use App\HospitalStaff;
use Maatwebsite\Excel\Row;

class ContractInformationImport extends ImportBAbstract
{
    /**
     * @return array
     */
    public function getColumns(): array
    {
        return [
            'HOSPITAL_CODE',
            'SHOPOWNER_NO',
            'SHOPOWNER_ID',
            'CONTRACT_NO',
            'SHOPOWNER_NAME_KANA',
            'SHOPOWNER_NAME',
            'CUST_NO',
            'CUST_NAME_KANA',
            'CUST_NAME',
            'SHOP_NAME',
            'DELEGATE_NAME_KANA',
            'DELEGATE_NAME',
            'POSTCODE',
            'PROV_KANA',
            'PROV_CODE',
            'CITY_KANA',
            'CITY',
            'DISTRICT_KANA',
            'DISTRICT',
            'BUILDING_NAME_KANA',
            'BUILDING_NAME',
            'TEL',
            'FAX',
            'EMAIL',
            'AGENT_CODE',
            'CHARGE_FEE_GROUP',
            'NEW_APPLY_DATE',
            'TERM_APPLY_DATE',
            'TERM_EXPECTED_DATE',
            'SPECIFICFEE_START_DATE',
            'SHOPOWNER_INFO_UPDATE_USER_NO',
            'SHOPOWNER_INFO_UPDATE_DATE',
            'TERM_DATE',
            'TERM_CANCEL_DATE',
            'ACCT_ISSUE_USER_ID',
            'ACCT_ISSUE_DATE',
            'ACCT_EMAIL_SEND_DATE',
            'INIT_PWD',
            'APPLY_EMAIL',
            'SITE_STATUS',
            'DOMAIN',
            'TEMP_DOMAIN',
            'LOGIN_URL',
            'HOMEPAGE_URL',
            'PHYSICS_DELETE_EXPECTED_DATE',
            'URL_SECURE_ID',
            'PAYPAL_SET_DATE',
            'PAYPAL_UNSET_DATE',
            'RANK',
            'OPEN_DATE',
            'REGION_ID',
            'CREATE_USER_NO',
            'CREATE_DATE',
            'MODIFY_USER_NO',
            'MODIFY_DATE',
            'DATA_VERSION',
        ];
    }

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
            'id' => $this->getValue($row, null), // @todo,
            'contractor_name_kana' => $this->getValue($row, 'SHOPOWNER_NAME_KANA'),
            'contractor_name' => $this->getValue($row, 'SHOPOWNER_NAME'),
            'application_date' => $this->getValue($row, 'NEW_APPLY_DATE'),
            'billing_start_date' => $this->getValue($row, 'SPECIFICFEE_START_DATE'),
            'cancellation_date' => $this->getValue($row, 'TERM_DATE'),
            'representative_name_kana' => $this->getValue($row, 'DELEGATE_NAME_KANA'),
            'representative_name' => $this->getValue($row, 'DELEGATE_NAME'),
            'postcode' => $this->getValue($row, 'POSTCODE'),
            'address' => $this->getValue($row, null), // @todo 文字列で都道府県から接続？？？
            'tel' => $this->getValue($row, 'TEL'),
            'fax' => $this->getValue($row, 'FAX'),
            'karada_dog_id' => $this->hospital_no, // @todo 確認
            'code' => null, // @todo 対応するデータ不明
            'old_karada_dog_id' => $this->hospital_no, // @todo 確認
            'hospital_staff_id' => $this->getStaffIdByName($this->getValue($row, 'DELEGATE_NAME')), // @todo 確認
            'created_at' => $this->getValue($row, 'CREATE_DATE'),
            'updated_at' => $this->getValue($row, 'MODIFY_DATE'),
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
