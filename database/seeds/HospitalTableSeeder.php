<?php

use App\Hospital;
use App\MedicalTreatmentTime;
use Illuminate\Database\Seeder;
use App\HospitalMajorClassification;
use App\HospitalMiddleClassification;
use App\HospitalMinorClassification;
use App\HospitalDetail;
use App\ReceptionEmailSetting;

class HospitalTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Hospital::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        factory(HospitalMajorClassification::class, 1)->create()->each(function ($major) {
            for ($i = 1; $i <= 14; $i++) {
                if ($i === 1) {
                    $middle = factory(HospitalMiddleClassification::class)->create([
                        'major_classification_id' => $major->id,
                        'name' => 'アクセスについて'
                    ]);
                    factory(HospitalMinorClassification::class)->create([
                        'middle_classification_id' => $middle->id,
                        'name' => '駐車場あり',
                        'is_fregist' => '1',

                    ]);
                    factory(HospitalMinorClassification::class)->create([
                        'middle_classification_id' => $middle->id,
                        'name' => 'その他',
                        'is_fregist' => '0',

                    ]);
                    factory(HospitalMinorClassification::class)->create([
                        'middle_classification_id' => $middle->id,
                        'name' => '送迎サービスあり',
                        'is_fregist' => '1',

                    ]);
                    factory(HospitalMinorClassification::class)->create([
                        'middle_classification_id' => $middle->id,
                        'name' => '駅近',
                        'is_fregist' => '1',

                    ]);
                } elseif ($i === 2) {
                    $middle = factory(HospitalMiddleClassification::class)->create([
                        'major_classification_id' => $major->id,
                        'name' => 'クレジットカード対応'
                    ]);
                    factory(HospitalMinorClassification::class)->create([
                        'middle_classification_id' => $middle->id,
                        'name' => 'その他',
                        'is_fregist' => '0',

                    ]);
                } elseif ($i === 3) {
                    $middle = factory(HospitalMiddleClassification::class)->create([
                        'major_classification_id' => $major->id,
                        'name' => '外国語対応'
                    ]);
                    factory(HospitalMinorClassification::class)->create([
                        'middle_classification_id' => $middle->id,
                        'name' => '英語',
                        'is_fregist' => '1',

                    ]);
                    factory(HospitalMinorClassification::class)->create([
                        'middle_classification_id' => $middle->id,
                        'name' => '中国語',
                        'is_fregist' => '1',

                    ]);
                    factory(HospitalMinorClassification::class)->create([
                        'middle_classification_id' => $middle->id,
                        'name' => '韓国語',
                        'is_fregist' => '1',

                    ]);
                    factory(HospitalMinorClassification::class)->create([
                        'middle_classification_id' => $middle->id,
                        'name' => 'その他',
                        'is_fregist' => '0',

                    ]);
                } elseif ($i === 4) {
                    $middle = factory(HospitalMiddleClassification::class)->create([
                        'major_classification_id' => $major->id,
                        'name' => '認定施設について'
                    ]);
                    factory(HospitalMinorClassification::class)->create([
                        'middle_classification_id' => $middle->id,
                        'name' => '日本人間ドック学会 機能評価認定施設',
                        'is_fregist' => '1',

                    ]);
                } elseif ($i === 5) {
                    $middle = factory(HospitalMiddleClassification::class)->create([
                        'major_classification_id' => $major->id,
                        'name' => '女性対応'
                    ]);
                    factory(HospitalMinorClassification::class)->create([
                        'middle_classification_id' => $middle->id,
                        'name' => 'レディースデーあり',
                        'is_fregist' => '1',

                    ]);
                    factory(HospitalMinorClassification::class)->create([
                        'middle_classification_id' => $middle->id,
                        'name' => '女性専用施設あり',
                        'is_fregist' => '1',

                    ]);
                    factory(HospitalMinorClassification::class)->create([
                        'middle_classification_id' => $middle->id,
                        'name' => 'パウダールームあり',
                        'is_fregist' => '1',

                    ]);
                } elseif ($i === 6) {
                    $middle = factory(HospitalMiddleClassification::class)->create([
                        'major_classification_id' => $major->id,
                        'name' => 'お子様対応'
                    ]);
                    factory(HospitalMinorClassification::class)->create([
                        'middle_classification_id' => $middle->id,
                        'name' => 'キッズスペース',
                        'is_fregist' => '1',

                    ]);
                    factory(HospitalMinorClassification::class)->create([
                        'middle_classification_id' => $middle->id,
                        'name' => '託児所',
                        'is_fregist' => '1',

                    ]);
                    factory(HospitalMinorClassification::class)->create([
                        'middle_classification_id' => $middle->id,
                        'name' => '子連れ対応可能',
                        'is_fregist' => '1',

                    ]);
                    factory(HospitalMinorClassification::class)->create([
                        'middle_classification_id' => $middle->id,
                        'name' => 'その他',
                        'is_fregist' => '0',

                    ]);
                } elseif ($i === 7) {
                    $middle = factory(HospitalMiddleClassification::class)->create([
                        'major_classification_id' => $major->id,
                        'name' => '施設について'
                    ]);
                    factory(HospitalMinorClassification::class)->create([
                        'middle_classification_id' => $middle->id,
                        'name' => '検診専用施設',
                        'is_fregist' => '1',

                    ]);
                    factory(HospitalMinorClassification::class)->create([
                        'middle_classification_id' => $middle->id,
                        'name' => '検診専用エリアあり',
                        'is_fregist' => '1',

                    ]);
                    factory(HospitalMinorClassification::class)->create([
                        'middle_classification_id' => $middle->id,
                        'name' => 'バリアフリー対応',
                        'is_fregist' => '1',

                    ]);
                } elseif ($i === 8) {
                    $middle = factory(HospitalMiddleClassification::class)->create([
                        'major_classification_id' => $major->id,
                        'name' => '食事について'
                    ]);
                    factory(HospitalMinorClassification::class)->create([
                        'middle_classification_id' => $middle->id,
                        'name' => '院内食堂・レストランあり（予約なしで利用可）',
                        'is_fregist' => '1',

                    ]);
                } elseif ($i === 9) {
                    $middle = factory(HospitalMiddleClassification::class)->create([
                        'major_classification_id' => $major->id,
                        'name' => '併用施設について'
                    ]);
                    factory(HospitalMinorClassification::class)->create([
                        'middle_classification_id' => $middle->id,
                        'name' => 'その他',
                        'is_fregist' => '0',

                    ]);
                } elseif ($i === 10) {
                    $middle = factory(HospitalMiddleClassification::class)->create([
                        'major_classification_id' => $major->id,
                        'name' => '周辺施設について'
                    ]);
                    factory(HospitalMinorClassification::class)->create([
                        'middle_classification_id' => $middle->id,
                        'name' => 'その他',
                        'is_fregist' => '0',

                    ]);
                } elseif ($i === 11) {
                    $middle = factory(HospitalMiddleClassification::class)->create([
                        'major_classification_id' => $major->id,
                        'name' => 'プライバシー配慮'
                    ]);
                    factory(HospitalMinorClassification::class)->create([
                        'middle_classification_id' => $middle->id,
                        'name' => '更衣室専有あり（一人着替えスペース）',
                        'is_fregist' => '1',

                    ]);
                    factory(HospitalMinorClassification::class)->create([
                        'middle_classification_id' => $middle->id,
                        'name' => '個室採血室あり',
                        'is_fregist' => '1',

                    ]);
                    factory(HospitalMinorClassification::class)->create([
                        'middle_classification_id' => $middle->id,
                        'name' => '個室回復室あり',
                        'is_fregist' => '1',

                    ]);
                    factory(HospitalMinorClassification::class)->create([
                        'middle_classification_id' => $middle->id,
                        'name' => '呼び出し配慮あり',
                        'is_fregist' => '1',

                    ]);
                } elseif ($i === 12) {
                    $middle = factory(HospitalMiddleClassification::class)->create([
                        'major_classification_id' => $major->id,
                        'name' => '検査結果'
                    ]);
                    factory(HospitalMinorClassification::class)->create([
                        'middle_classification_id' => $middle->id,
                        'name' => '検査結果即日発行対応',
                        'is_fregist' => '1',

                    ]);
                } elseif ($i === 13) {
                    $middle = factory(HospitalMiddleClassification::class)->create([
                        'major_classification_id' => $major->id,
                        'name' => 'フリーエリア'
                    ]);
                    factory(HospitalMinorClassification::class)->create([
                        'middle_classification_id' => $middle->id,
                        'name' => 'その他',
                        'is_fregist' => '0',

                    ]);
                } elseif ($i === 14) {
                    $middle = factory(HospitalMiddleClassification::class)->create([
                        'major_classification_id' => $major->id,
                        'name' => '検索ワード'
                    ]);
                    factory(HospitalMinorClassification::class)->create([
                        'middle_classification_id' => $middle->id,
                        'name' => 'その他',
                        'is_fregist' => '0',

                    ]);
                }
            };
        });

        $minors = HospitalMinorClassification::all()->toArray();

        factory(Hospital::class, 50)->create()->each(function ($hospital) use ($minors) {
            factory(ReceptionEmailSetting::class)->create(['hospital_id' => $hospital->id]);
            factory(MedicalTreatmentTime::class)->create(['hospital_id' => $hospital->id]);
            foreach ($minors as $minor) {
                factory(HospitalDetail::class)->create([
                    'hospital_id' => $hospital->id,
                    'minor_classification_id' => $minor['id'],
                ]);
            }
        });
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Hospital::reguard();
    }
}
