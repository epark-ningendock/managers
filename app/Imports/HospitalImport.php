<?php

namespace App\Imports;

use App\ContractPlan;
use App\ConvertedIdString;
use App\DistrictCode;
use App\Hospital;
use App\HospitalMeta;
use App\Prefecture;
use App\Rail;
use App\Station;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Row;

class HospitalImport extends ImportAbstract implements WithChunkReading
{
    /**
     * 旧システムのインポート対象テーブルのプライマリーキーを返す
     * @return string
     */
    public function getOldPrimaryKeyName(): string
    {
        return 'no';
    }

    /**
     * 新システムの対象クラス名を返す
     * @return string
     */
    public function getNewClassName(): string
    {
        return Hospital::class;
    }

    /**
     * @param Row $row
     * @throws \Exception
     */
    public function onRow(Row $row)
    {
        $row = $row->toArray();

//        if (is_null($row['pref'])) {
//            return;
//        }

        $district_id = 0;
        if (!empty($row['district_no']) && $row['district_no'] != '') {
            $district = DistrictCode::where('district_code', $row['district_no'])->first();
            if ($district) {
                $district_id = $district->id;
            }
        }

        $deleted_at = null;
        if ($row['status'] == 'X') {
            $deleted_at = $row['updt'];
        }


        $model = new Hospital([
            'old_karada_dog_id' => $row['id'],
            'name' => $row['name'],
            'kana' => $row['kana'],
            'postcode' => $row['zip'],
            'district_code_id' => $district_id,
            'course_meta_information_id' => null,
            'address1' => $row['address1'],
            'address2' => $row['address2'],
            'longitude' => $row['x'],
            'latitude' => $row['y'],
            'direction' => $row['direction'],
            'streetview_url' => $row['streetview_url'],
            'tel' => $row['tel'],
            'paycall' => $row['paycall'],
            'fax' => $row['fax'],
            'url' => $row['url'],
            'consultation_note' => $row['consultation_note'],
            'memo' => $row['memo'],
            'rail1' => $row['rail1'],
            'station1' => $row['station1'],
            'access1' => $row['access1'],
            'rail2' => $row['rail2'],
            'station2' => $row['station2'],
            'access2' => $row['access2'],
            'rail3' => $row['rail3'],
            'station3' => $row['station3'],
            'access3' => $row['access3'],
            'rail4' => $row['rail4'],
            'station4' => $row['station4'],
            'access4' => $row['access4'],
            'rail5' => $row['rail5'],
            'station5' => $row['station5'],
            'access5' => $row['access5'],
            'memo1' => $row['memo1'],
            'memo2' => $row['memo2'],
            'memo3' => $row['memo3'],
            'principal' => $row['principal'],
            'principal_history' => $row['principal_history'],
            'pv_count' => $row['pv'],
            'pvad' => $row['pvad'],
            'is_pickup' => $row['pickup'],
            'hospital_staff_id' => 0,
            'status' => $row['status'],
            'free_area' => $row['free_area'],
            'search_word' => $row['search_word'],
            'plan_code' => $row['plan_cd'],
            'hplink_contract_type' => $row['hplink_contract_kbn'],
            'hplink_count' => $row['hplink_count'],
            'hplink_price' => $row['hplink_price'],
            'is_pre_account' => $row['flg_pre_account'],
            'pre_account_discount_rate' => $row['pre_account_discount_rate'],
            'pre_account_commission_rate' => $row['pre_account_commission_rate'],
            'created_at' => $row['rgst'],
            'updated_at' => $row['updt'],
            'prefecture_id' => $row['pref'],
            'deleted_at' => $deleted_at,
        ]);

        $model->save();

        $this->createHospitalMeta($model);

        // 医療機関プラン
        $contract_plan = ContractPlan::query()->where('plan_code', sprintf('Y0%02d', $row['plan_cd']))->first();

        if (!is_null($contract_plan)) {
            $model->hospitalPlans()->create([
                'contract_plan_id' => $contract_plan->id,
                'from' => '2019-01-01',
                'to' => null,
            ]);
        }

        // 請求
        $model->billings()->create([
            'billing_month' => now()->addMonth(1)->format('Ym'),
            'status' => 1
        ]);

//        $this->deleteIf($model, $row, 'status', ['X']);
        $this->setId($model, $row);
        ConvertedIdString::firstOrCreate([
            'table_name' => 'hospitals',
            'old_id' => $row['id'],
            'hospital_no' => $row['id'],
        ], [
            'table_name' => 'hospitals',
            'old_id' => $row['id'],
            'new_id' => $model->id,
            'hospital_no' => $row['id'],
        ]);
    }

    /**
     * @param $hospital
     */
    private function createHospitalMeta($hospital) {
        $area_station = '';
        if (!empty($hospital->prefecture_id)) {
            $prefecture = Prefecture::find($hospital->prefecture_id);
            if ($prefecture) {
                $area_station = $prefecture->name . ' ';
            }
        }
        if (!empty($hospital->district_code_id)) {
            $district = DistrictCode::find($hospital->district_code_id);
            if ($district) {
                $area_station = $area_station . $district->name . ' ';
            }
        }
        if (!empty($hospital->address1)) {
            $area_station = $area_station . $hospital->address1 . ' ';
        }

        if (!empty($hospital->rail1)) {
            $rail1 = Rail::find($hospital->rail1);
            if ($rail1) {
                $area_station = $area_station . $rail1->name . ' ';
            }
        }

        if (!empty($hospital->station1)) {
            $station1 = Station::find($hospital->station1);
            if ($station1) {
                $area_station = $area_station . $station1->name . ' ';
            }
        }

        if (!empty($hospital->rail2)) {
            $rail2 = Rail::find($hospital->rail2);
            if ($rail2) {
                $area_station = $area_station . $rail2->name . ' ';
            }
        }

        if (!empty($hospital->station2)) {
            $station2 = Station::find($hospital->station2);
            if ($station2) {
                $area_station = $area_station . $station2->name . ' ';
            }
        }

        if (!empty($hospital->rail3)) {
            $rail3 = Rail::find($hospital->rail3);
            if ($rail3) {
                $area_station = $area_station . $rail3->name . ' ';
            }
        }

        if (!empty($hospital->station3)) {
            $station3 = Station::find($hospital->station3);
            if ($station3) {
                $area_station = $area_station . $station3->name . ' ';
            }
        }

        if (!empty($hospital->rail4)) {
            $rail4 = Rail::find($hospital->rail4);
            if ($rail4) {
                $area_station = $area_station . $rail4->name . ' ';
            }
        }

        if (!empty($hospital->station4)) {
            $station4 = Station::find($hospital->station4);
            if ($station4) {
                $area_station = $area_station . $station4->name . ' ';
            }
        }

        if (!empty($hospital->rail5)) {
            $rail5 = Rail::find($hospital->rail5);
            if ($rail5) {
                $area_station = $area_station . $rail5->name . ' ';
            }
        }

        if (!empty($hospital->station5)) {
            $station5 = Station::find($hospital->station5);
            if ($station5) {
                $area_station = $area_station . $station5->name . ' ';
            }
        }

        $hospital_meta = new HospitalMeta();
        $hospital_meta->hospital_id = $hospital->id;
        $hospital_meta->hospital_name = $hospital->name . ' ' . $hospital->kana;
        $hospital_meta->area_station = $area_station;

        $hospital_meta->save();
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
