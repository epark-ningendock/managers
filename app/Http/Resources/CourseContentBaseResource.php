<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseContentBaseResource extends JsonResource
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
        if (!isset($course_details)) return;
        $categories = $course_details->map(function ($c) {
            return [
                'id' => $c->major_classification->id,
                'title' => $c->major_classification->name,
                'type_no' => $c->major_classification->classification_type_id,
                'category_middle' => $c->major_classification->middle_classifications->map(function ($md) {
                    return [
                        'id' => $md->id,
                        'title' => $md->name,
                        'category_small' => $md->minor_classifications->map(function ($mn) {
                            return [
                                'id' => $mn->id,
                                'title' => $mn->name,
                                'icon' => $mn->icon_name,
                            ];
                        }),
                    ];
                }),
            ];
        });
        return $categories;
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
