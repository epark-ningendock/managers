<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class CourseContentBaseResource extends Resource
{
    /**
     * 検査コースコンテンツ基本情報 resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->baseCollections()->toArray();
    }

    /**
     * 検査コースコンテンツ基本情報
     *
     * @return Illuminate\Support\Collection
     */
    protected function baseCollections()
    {
        return collect(
            [
                'category' => $this->_categories($this->course_details),
                'course_option' => $this->_options($this->course_options),
                'question' => $this->_questions($this->course_questions),
            ]
        );
    }

    /**
     * コースカテゴリ情報生成
     * 
     * @param  コース詳細
     * @return カテゴリ
     */
    private function _categories($course_details)
    {
        $results = [];
        foreach ($course_details as $detail) {

            if ($detail->select_status != 1 || $detail->status != '1') {
                continue;
            }

            $keyIndex = array_search($detail->major_classification_id, array_column($results, 'id'));

            if ($keyIndex === false) {
                $major = [
                    'id' => $detail->major_classification->id,
                    'title' => $detail->major_classification->name,
                    'type_no' => $detail->major_classification->classification_type_id,
                    'category_middle' => $this->getMiddle($detail->middle_classification, $detail->minor_classification)];
                $results[] = $major;
            } else {
                $major = $results[$keyIndex];
                $middleKeyIndex = array_search($detail->middle_classification_id, array_column($major['category_middle'], 'id'));
                if ($middleKeyIndex === false ) {
                    $middle = $this->getMiddle($detail->middle_classification, $detail->minor_classification);
                    $category_middle = $major['category_middle'];
                    $major['category_middle'] = array_merge($category_middle, $middle);
                } else {
                    if (isset($major['category_middle'][$middleKeyIndex])) {
                        $middle = $major['category_middle'][$middleKeyIndex];
                        $minorKeyIndex = array_search($detail->minor_classification_id, array_column($middle['category_small'], 'id'));
                        if ($minorKeyIndex === false) {
                            $minor = $this->getMinor($detail->minor_classification);
                            $category_small = $middle['category_small'];
                            $middle['category_small'] = array_merge($category_small, $minor);
                        }
                    }
                }
            }
        }
        return $results;
//        if (!isset($course_details)) return;
//        $categories = $course_details->map(function ($c) {
//            if (in_array($c->major_classification->classification_type_id, [2,3,4,5])) {
//                return [
//                    'id' => $c->major_classification->id,
//                    'title' => $c->major_classification->name,
//                    'type_no' => $c->major_classification->classification_type_id,
//                    'category_middle' => $c->major_classification->middle_classifications->map(function ($md) {
//                        return [
//                            'id' => $md->id,
//                            'title' => $md->name,
//                            'category_small' => $md->minor_classifications->map(function ($mn) {
//                                return [
//                                    'id' => $mn->id,
//                                    'title' => $mn->name,
//                                    'icon' => $mn->icon_name,
//                                ];
//                            }),
//                        ];
//                    }),
//                ];
//            }
//        });
//        return $categories;
    }

    private function getMiddle($middle_classification, $minor_classfication) {

        return ['id' => $middle_classification->id,
            'title' => $middle_classification->icon_name,
            'category_small' => $this->getMinor($minor_classfication)];
    }

    private function getMinor($minor_classfication) {
        return ['id' => $minor_classfication->id,
            'title' => $minor_classfication->icon_name,
            'icon' => $minor_classfication->name];
    }

    /**
     * コースオプション情報生成
     * 
     * @param  コースオプション
     * @return コースオプション
     */
    private function _options($course_options)
    {
        if (!isset($course_options)) return;
        $options = $course_options->map(function ($o) {
            return [
                'option' => [
                    'cd' => $o->option->id,
                    'title' => $o->option->name,
                    'confirm' => $o->option->confirm,
                    'price' => $o->option->price,
                    'tax_class' => $o->option->tax_class_id,
                ],
            ];
        });
        return $options;
    }

    /**
     * コース質問生成
     * 
     * @param  コース質問
     * @return コース質問
     */
    private function _questions($course_questions)
    {
        if (!isset($course_questions)) return;
        $questions = $course_questions->map(function ($q) {
            $answers = collect(
                [
                    ['no' => 1, 'text' => $q->answer01 ?? ''],
                    ['no' => 2, 'text' => $q->answer02 ?? ''],
                    ['no' => 3, 'text' => $q->answer03 ?? ''],
                    ['no' => 4, 'text' => $q->answer04 ?? ''],
                    ['no' => 5, 'text' => $q->answer05 ?? ''],
                    ['no' => 6, 'text' => $q->answer06 ?? ''],
                    ['no' => 7, 'text' => $q->answer07 ?? ''],
                    ['no' => 8, 'text' => $q->answer08 ?? ''],
                    ['no' => 9, 'text' => $q->answer09 ?? ''],
                    ['no' => 10, 'text' => $q->answer10 ?? ''],
                ]
            );
            return [
                'no' => $q->question_number,
                'text' => $q->question_title,
                'answer' => $answers->map(function ($a) {
                    return $a;
                }),
            ];
        });
        return $questions;
    }
}
