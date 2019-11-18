<?php

namespace App\Imports;

use App\ContractInformation;
use App\ConvertedId;
use App\Hospital;
use App\HospitalStaff;
use Carbon\Carbon;
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

        $hospital = Hospital::withTrashed()->where('old_karada_dog_id', $this->hospital_no)->get()->first();

        $c = ConvertedId::where('table_name', 'hospitals')
            ->where('new_id', $hospital->id)
            ->first();

        $application_date = Carbon::create('20170101');
        if (!empty($this->getValue($row, 'NEW_APPLY_DATE'))) {
            $application_date = $this->getValue($row, 'NEW_APPLY_DATE');
        }

        $billing_start_date = Carbon::create('20170101');
        if (!empty($this->getValue($row, 'SPECIFICFEE_START_DATE'))) {
            $billing_start_date = $this->getValue($row, 'SPECIFICFEE_START_DATE');
        }
       $cancel_date = null;
        if (!empty($this->getValue($row, 'TERM_EXPECTED_DATE'))) {
            $cancel_date = $this->getValue($row, 'TERM_EXPECTED_DATE');
        }

        $service_start_date = Carbon::create('20170101');
        if (!empty($this->getValue($row, 'SPECIFICFEE_START_DATE'))) {
            $cancel_date = $this->getValue($row, 'SPECIFICFEE_START_DATE');
        }

        $service_end_date = Carbon::create('20221231');
        if (!empty($this->getValue($row, 'TERM_EXPECTED_DATE'))) {
            $service_end_date = $this->getValue($row, 'TERM_EXPECTED_DATE');
        }


        $model = new ContractInformation([
            'contractor_name_kana' => $this->getValue($row, 'SHOPOWNER_NAME_KANA'),
            'contractor_name' => $this->getValue($row, 'SHOPOWNER_NAME'),
            'application_date' =>$application_date,
            'billing_start_date' => $billing_start_date,
            'cancellation_date' => $cancel_date,
            'representative_name_kana' => $this->getValue($row, 'DELEGATE_NAME_KANA'),
            'representative_name' => $this->getValue($row, 'DELEGATE_NAME'),
            'postcode' => $this->getValue($row, 'POSTCODE'),
            'address' => null, // @todo 文字列で都道府県から接続？？？
            'tel' => $this->getValue($row, 'TEL'),
            'fax' => $this->getValue($row, 'FAX'),
            'karada_dog_id' => $this->hospital_no, // @todo 確認
            'customer_no' => $this->getValue($row, 'CUST_NO'),
            'code' => 'D'.sprintf('%05d',  $c->old_id),
            'old_karada_dog_id' => $this->hospital_no, // @todo 確認
            'hospital_staff_id' => $this->getStaffIdByName($this->getValue($row, 'DELEGATE_NAME')), // @todo 確認
            'created_at' => $this->getValue($row, 'CREATE_DATE'),
            'updated_at' => $this->getValue($row, 'MODIFY_DATE'),
            'property_no' => $this->getValue($row, 'CONTRACT_NO'),
            'contract_plan_id' => sprintf('Y0%02d', $hospital->plan_code),
            'hospital_id' => $hospital->id,
            'service_start_date' => $service_start_date,
            'service_end_date' => $service_end_date
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

    public function batchSize(): int
    {
        return 10000;
    }
    public function chunkSize(): int
    {
        return 10000;
    }
}
