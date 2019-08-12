<?php

use Illuminate\Database\Seeder;
use App\ClassificationType;
use App\MinorClassification;
use App\MiddleClassification;
use App\MajorClassification;

class ClassificationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        // コースの特徴登録
        $this->courseRegist();
        // コース基本情報登録
        $this->courseTypeRegist();
    }

    protected function courseTypeRegist()
    {
        $typeList = [ 'middleName' => '検査種別',
                    'minorNameList' => [ [ 'name' =>'人間ドッグ', 'is_fregist' => 1 ],
                                        [ 'name' => '脳ドック', 'is_fregist' => 1],
                                        [ 'name' =>'PET検診', 'is_fregist' => 1 ],
                                        [ 'name' =>'心臓ドック', 'is_fregist' => 1 ],
                                        [ 'name' =>'胃がん検診', 'is_fregist' => 1 ],
                                        [ 'name' =>'大腸がん検診', 'is_fregist' => 1 ],
                                        [ 'name' =>'肺がん検診', 'is_fregist' => 1 ],
                                        [ 'name' =>'健康診断', 'is_fregist' => 1 ],
                                        [ 'name' =>'メンズドック', 'is_fregist' => 1 ],
                                        [ 'name' =>'レディースドック', 'is_fregist' => 1 ] ] ];

        $ageList =  [ 'middleName' => '検査条件（年齢）',
                     'minorNameList' => [ [ 'name' =>'~20歳', 'is_fregist' => 1 ],
                                         [ 'name' =>'21歳~30歳', 'is_fregist' => 1 ],
                                         [ 'name' =>'31歳~40歳', 'is_fregist' => 1 ],
                                         [ 'name' =>'41歳~50歳', 'is_fregist' => 1 ],
                                         [ 'name' =>'51歳~60歳', 'is_fregist' => 1 ],
                                         [ 'name' =>'60歳~', 'is_fregist' => 1 ] ]];

        $regitstList = [ [ 'typeName' => 'コース基本情報',
                      'nameList' => [ ['majorName' =>'検査種別',
                                       'middleNameList' => [ $typeList] ],
                                       ['majorName' =>'検査条件',
                                        'middleNameList' => [ $ageList] ],
                                        ] ] ];
        $this->registRecord($regitstList);
    }

    protected function courseRegist()
    {
        $womanList =  [ 'middleName' => '女性医師・スタッフ対応',
                                 'minorNameList' => [ [ 'name' =>'女性スタッフ対応', 'is_fregist' => 1 ], [ 'name' =>'女性医師対応', 'is_fregist' => 1 ] ] ];

        $restList =  [ 'middleName' => '休日検診',
                                'minorNameList' => [ [ 'name' =>'土曜検査実施可', 'is_fregist' => 1 ], [ 'name' =>'日曜検査実施可', 'is_fregist' => 1 ], [ 'name' =>'祝日検査実施可', 'is_fregist' => 1 ] ] ];

        $courseList = [ [ 'typeName' => 'コースの特徴',
                      'nameList' => [ ['majorName' =>'検査コース特徴アイコン',
                                       'middleNameList' => [ $womanList, $restList] ] ] ] ];
        // コースの特徴登録
        $this->registRecord($courseList);
    }

    protected function registRecord($dataList)
    {
        for ($i = 0; $i < count($dataList); $i++) {
            // echo(count($dataList));
            $classificationType = ClassificationType::create([
            'name' => $dataList[$i]['typeName'],
            'order' => 0,
            'status' => 1,
            'is_editable' => 1,
        ]);

            for ($j = 0; $j < count($dataList[$i]['nameList']); $j++) {
                // echo($j);
                // echo(count($dataList[$i]['nameList']));
                $majorClassification = MajorClassification::create([
                'name' => $dataList[$i]['nameList'][$j]['majorName'],
                'order' => 0,
                'status' => 1,
                'is_icon' => 0,
                'classification_type_id' => $classificationType->id,
            ]);

                for ($k = 0; $k < count($dataList[$i]['nameList'][$j]['middleNameList']); $k++) {
                    $middleClassification = MiddleClassification::create([
                'name' => $dataList[$i]['nameList'][$j]['middleNameList'][$k]['middleName'],
                'order' => 0,
                'status' => 1,
                'is_icon' => 0,
                'major_classification_id' => $majorClassification->id,
            ]);

                    for ($l = 0; $l < count($dataList[$i]['nameList'][$j]['middleNameList'][$k]['minorNameList']); $l++) {
                        $minorClassification = MinorClassification::create([
                  'name' => $dataList[$i]['nameList'][$j]['middleNameList'][$k]['minorNameList'][$l]['name'],
                  'is_fregist' => 1,
                  'order' => 0,
                  'status' => 1,
                  'max_length' => 0,
                  'is_icon' => 0,
                  'major_classification_id' => $majorClassification->id,
                  'middle_classification_id' => $middleClassification->id,
              ]);
                    };
                };
            };
        };
    }
}
