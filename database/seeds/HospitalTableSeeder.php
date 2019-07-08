<?php

use App\Hospital;
use App\MedicalTreatmentTime;
use Illuminate\Database\Seeder;
use App\HospitalMajorClassification;
use App\HospitalMiddleClassification;
use App\HospitalMinorClassification;
use App\HospitalDetail;

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
            factory(HospitalMiddleClassification::class, 10)->create(['major_classification_id' => $major->id])->each(function ($middle) {
                factory(HospitalMinorClassification::class, 3)->make()->each(function ($minor) use ($middle) {
                    $is_fregist = '1';
                    $name = '';
                    
                    if ($middle->name === "アクセスについて") {
                        $names = [
                            '駐車場あり',
                            '送迎サービスあり',
                            '駅近',
                            'その他'
                        ];
                        $name = $names[rand(0, 3)];
                    } elseif ($middle->name === "外国語対応") {
                        $names = [
                            '英語',
                            '中国語',
                            '韓国語',
                            'その他'
                        ];
                        $name = $names[rand(0, 3)];
                    } elseif ($middle->name === "女性対応") {
                        $names = [
                            'レディースデーあり',
                            '女性専用エリアあり',
                            'パウダールームあり'
                        ];
                        $name = $names[rand(0, 2)];
                    } elseif ($middle->name === "お子様対応") {
                        $names = [
                            'キッズスペース',
                            '託児所',
                            '子連れ対応可能',
                            'その他'
                        ];
                        $name = $names[rand(0, 3)];
                    } elseif ($middle->name === "施設について") {
                        $names = [
                            '検診専用施設',
                            '検診専用エリアあり',
                            'バリアフリー対応'
                        ];
                        $name = $names[rand(0, 2)];
                    } elseif ($middle->name === "プライバシー配慮") {
                        $names = [
                            '更衣室専有あり（一人着替えスペース）',
                            '個室採血室あり',
                            '個室回復室あり',
                            '呼び出し配慮あり'
                        ];
                        $name = $names[rand(0, 3)];
                    }
    
                    if ($name === "その他") {
                        $is_fregist = '0';
                    }
                    
                    $minor->middle_classification_id = $middle->id;
                    $minor->name = $name;
                    $minor->is_fregist = $is_fregist;
                    $minor->save();
                });
            });
        });

        $minors = HospitalMinorClassification::all()->toArray();

        factory(Hospital::class, 50)->create()->each(function ($hospital) use ($minors) {
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
