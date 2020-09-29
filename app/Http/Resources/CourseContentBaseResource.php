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

        if (empty($course_details)) {
            return $results;
        }

        $major_ids = [];
        $middle_ids = [];
        $minor_ids = [];
        $major_id = 0;
        $middle_id = 0;
        $major = null;
        $middle = null;
        $i = 0;
        $d = null;
        foreach ($course_details as $detail) {

            if (($detail->select_status != 1 && empty($detail->inputstring)) || $detail->status != '1') {
                continue;
            }

            if ($major_id != $detail->major_classification_id) {
                if ($i != 0) {
                    $middle_ids[] = ['id' => $middle_id,
                        'title' => $middle->name,
                        'category_small' => $minor_ids];
                    $major_ids[] = ['id' => $major_id,
                        'title' => $major->name,
                        'type_no' => $major->classification_type_id,
                        'category_middle' => $middle_ids];
                }
                $middle_ids = [];
                $minor_ids = [];
                if ($detail->minor_classification->is_fregist == 1) {
                    $minor_ids[] = ['id' => $detail->minor_classification_id,
                        'title' => $detail->minor_classification->name,
                        'icon' => $detail->minor_classification->icon_name];
                } else {
                    $minor_ids[] = ['id' => $detail->minor_classification_id,
                        'title' => $detail->inputstring,
                        'icon' => $detail->inputstring];
                }

                $major_id = $detail->major_classification_id;
                $major = $detail->major_classification;
                $middle_id = $detail->middle_classification_id;
                $middle = $detail->middle_classification;
            } elseif ($middle_id != $detail->middle_classification_id) {
                $middle_ids[] = ['id' => $middle_id,
                    'title' => $middle->name,
                    'category_small' => $minor_ids];
                $minor_ids = [];

                if ($detail->minor_classification->is_fregist == 1) {
                    $minor_ids[] = ['id' => $detail->minor_classification_id,
                        'title' => $detail->minor_classification->name,
                        'icon' => $detail->minor_classification->icon_name];
                } else {
                    $minor_ids[] = ['id' => $detail->minor_classification_id,
                        'title' => $detail->inputstring,
                        'icon' => $detail->inputstring];
                }
                $middle_id = $detail->middle_classification_id;
                $middle = $detail->middle_classification;
            } else {
                if ($detail->minor_classification->is_fregist == 1) {
                    $minor_ids[] = ['id' => $detail->minor_classification_id,
                        'title' => $detail->minor_classification->name,
                        'icon' => $detail->minor_classification->icon_name];
                } else {
                    $minor_ids[] = ['id' => $detail->minor_classification_id,
                        'title' => $detail->inputstring,
                        'icon' => $detail->inputstring];
                }
            }

            $i++;
            $d = $detail;
        }

        if (isset($middle) && isset($major)) {
            $middle_ids[] = ['id' => $middle_id,
                'title' => $middle->name,
                'category_small' => $minor_ids];
            $major_ids[] = ['id' => $major_id,
                'title' => $major->name,
                'type_no' => $major->classification_type_id,
                'category_middle' => $middle_ids];
        }

        return $major_ids;
    }

    /**
     * コースオプション情報生成
     * 
     * @param  コースオプション
     * @return コースオプション
     */
    private function _options($course_options)
    {
        if (!isset($course_options)) return [];
        $options = [];
        foreach ($course_options as $o) {
            if (isset($o->option) && !empty($o->option->id)) {
                $options[] = [
                    'option' => [
                        'cd' => $o->option->id,
                        'title' => $o->option->name,
                        'confirm' => $o->option->confirm,
                        'price' => $o->option->price,
                        'tax_class' => $o->option->tax_class_id,
												'order' => $o->option->order,
                    ],
                ];
            }
        }

        return collect($options)->sortBy('order')->values()->all();
    }

    /**
     * コース質問生成
     * 
     * @param  コース質問
     * @return $questions
     */
    private function _questions($course_questions)
    {
        if (!isset($course_questions)) return [];

        $questions = [];
        foreach ($course_questions as $q) {
            if (isset($q) && ($q->is_question == 0) && !empty($q->question_title)) {
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
                    ]);
                    $questions[] = [
												'id' => $q->id,
                        'no' => $q->question_number,
                        'text' => $q->question_title,
                        'answer' => $answers->map(function ($a) {
                            return $a;
                        }),
                    ];
            }

        }

        return $questions;
    }
}
