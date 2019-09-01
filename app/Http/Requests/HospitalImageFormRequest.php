<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HospitalImageFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $tab_staff_valid = [];
        $tab_facility_valid = [];
        $tab_internal_valid = [];
        $tab_external_valid = [];
        $tab_another_valid = [];

        for ($i = 1; $i <= 30; $i++) {
            $tab_staff_valid += [
            "staff_tab_{$i}" => 'file|image|max:4000',
            "staff_tab_{$i}_order2" => 'nullable|max:99|numeric|min:1',
            "staff_tab_{$i}_memo2" => 'nullable|max:200',
            "staff_tab_{$i}_location" => 'nullable',
         ];
        }

        for ($i = 1; $i <= 30; $i++) {
            $tab_facility_valid += [
                "facility_tab_{$i}" => 'file|image|max:4000',
                "facility_tab_{$i}_order2" => 'nullable|max:99|numeric|min:1',
                "facility_tab_{$i}_memo2" => 'nullable|max:200',
                "facility_tab_{$i}_location" => 'nullable',
            ];
        }

        for ($i = 1; $i <= 30; $i++) {
            $tab_internal_valid += [
                "internal_tab_{$i}" => 'file|image|max:4000',
                "internal_tab_{$i}_order2" => 'nullable|max:99|numeric|min:1',
                "internal_tab_{$i}_memo2" => 'nullable|max:200',
                "internal_tab_{$i}_location" => 'nullable',
            ];
        }

        for ($i = 1; $i <= 30; $i++) {
            $tab_external_valid += [
                "external_tab_{$i}" => 'file|image|max:4000',
                "external_tab_{$i}_order2" => 'nullable|max:99|numeric|min:1',
                "external_tab_{$i}_memo2" => 'nullable|max:200',
                "external_tab_{$i}_location" => 'nullable',
            ];
        }

        for ($i = 1; $i <= 30; $i++) {
            $tab_another_valid += [
                "another_tab_{$i}" => 'file|image|max:4000',
                "another_tab_{$i}_order2" => 'nullable|max:99|numeric|min:1',
                "another_tab_{$i}_memo2" => 'nullable|max:200',
                "another_tab_{$i}_location" => 'nullable',
            ];
        }

        $valid = [
            'lock_version' => 'nullable',

            'main' => 'file|image|max:4000',
            'sub_1' => 'file|image|max:4000',
            'sub_2' => 'file|image|max:4000',
            'sub_3' => 'file|image|max:4000',
            'sub_4' => 'file|image|max:4000',
            'sub_5' => 'file|image|max:4000',

            'speciality_1' => 'file|image|max:4000',
            'speciality_1_title' => 'nullable|max:100',
            'speciality_1_caption' => 'nullable|max:5000',

            'speciality_2' => 'file|image|max:4000',
            'speciality_2_title' => 'nullable|max:100',
            'speciality_2_caption' => 'nullable|max:5000',

            'speciality_3' => 'file|image|max:4000',
            'speciality_3_title' => 'nullable|max:100',
            'speciality_3_caption' => 'nullable|max:5000',

            'speciality_4' => 'file|image|max:4000',
            'speciality_4_title' => 'nullable|max:100',
            'speciality_4_caption' => 'nullable|max:5000',

            'title' => 'nullable|max:100',
            'caption' => 'nullable|max:200',
            'map_url' => 'nullable|url',

            'tab_1' => 'file|image|max:4000',
            'tab_1_order1' => 'nullable|max:99|numeric',
            'tab_1_memo1' => 'nullable|max:200',
            'tab_1_memo2' => 'nullable|max:200',

            'tab_2' => 'file|image|max:4000',
            'tab_2_order1' => 'nullable|max:99|numeric',
            'tab_2_memo1' => 'nullable|max:200',
            'tab_2_memo2' => 'nullable|max:200',

            'tab_3' => 'file|image|max:4000',
            'tab_3_order1' => 'nullable|max:99|numeric',
            'tab_3_memo1' => 'nullable|max:200',
            'tab_3_memo2' => 'nullable|max:200',

            'tab_4' => 'file|image|max:4000',
            'tab_4_order1' => 'nullable|max:99|numeric',
            'tab_4_memo1' => 'nullable|max:200',
            'tab_4_memo2' => 'nullable|max:200',

            'tab_5' => 'file|image|max:4000',
            'tab_5_order1' => 'nullable|max:99|numeric',
            'tab_5_memo1' => 'nullable|max:200',
            'tab_5_memo2' => 'nullable|max:200',

            'staff_1' => 'file|image|max:5000',
            'staff_1_name' => 'nullable|max:100',
            'staff_1_career' => 'nullable|max:5000',
            'staff_1_memo' => 'nullable|max:5000',

            'staff_2' => 'file|image|max:5000',
            'staff_2_name' => 'nullable|max:100',
            'staff_2_career' => 'nullable|max:5000',
            'staff_2_memo' => 'nullable|max:5000',

            'staff_3' => 'file|image|max:5000',
            'staff_3_name' => 'nullable|max:100',
            'staff_3_career' => 'nullable|max:5000',
            'staff_3_memo' => 'nullable|max:5000',

            'staff_4' => 'file|image|max:5000',
            'staff_4_name' => 'nullable|max:100',
            'staff_4_career' => 'nullable|max:5000',
            'staff_4_memo' => 'nullable|max:5000',

            'staff_5' => 'file|image|max:5000',
            'staff_5_name' => 'nullable|max:100',
            'staff_5_career' => 'nullable|max:5000',
            'staff_5_memo' => 'nullable|max:5000',

            'staff_6' => 'file|image|max:5000',
            'staff_6_name' => 'nullable|max:100',
            'staff_6_career' => 'nullable|max:5000',
            'staff_6_memo' => 'nullable|max:5000',

            'staff_7' => 'file|image|max:5000',
            'staff_7_name' => 'nullable|max:100',
            'staff_7_career' => 'nullable|max:5000',
            'staff_7_memo' => 'nullable|max:5000',

            'staff_8' => 'file|image|max:5000',
            'staff_8_name' => 'nullable|max:100',
            'staff_8_career' => 'nullable|max:5000',
            'staff_8_memo' => 'nullable|max:5000',

            'staff_9' => 'file|image|max:5000',
            'staff_9_name' => 'nullable|max:100',
            'staff_9_career' => 'nullable|max:5000',
            'staff_9_memo' => 'nullable|max:5000',

            'staff_10' => 'file|image|max:5000',
            'staff_10_name' => 'nullable|max:100',
            'staff_10_career' => 'nullable|max:5000',
            'staff_10_memo' => 'nullable|max:5000',

            'interview_1' => 'file|image|max:4000',
            'interview_1_title' => 'nullable|max:100',
            'interview_1_caption' => 'nullable|max:5000',

            'interview.*.question' => 'max:5000',
            'interview.*.answer' => 'max:5000',

            'interview_new.*.question' => 'max:5000',
            'interview_new.*.answer' => 'max:5000',
        ];

        $valid = array_merge($valid, $tab_staff_valid);
        $valid = array_merge($valid, $tab_facility_valid);
        $valid = array_merge($valid, $tab_internal_valid);
        $valid = array_merge($valid, $tab_external_valid);
        $valid = array_merge($valid, $tab_another_valid);

        return $valid;
    }

    /**
     * 項目名
     *
     * @return array
     */
    public function attributes()
    {
        $tab_facility_attributes = [];
        for ($i = 1; $i <= 30; $i++) {
            $tab_facility_attributes += [
                "facility_tab_{$i}_order2" => '表示順',
                "facility_tab_{$i}_memo2" => '説明',
            ];
        }

        $tab_staff_attributes = [];
        for ($i = 1; $i <= 30; $i++) {
            $tab_staff_attributes += [
                "staff_tab_{$i}_order2" => '表示順',
                "staff_tab_{$i}_memo2" => '説明',
            ];
        }

        $tab_internal_attributes = [];
        for ($i = 1; $i <= 30; $i++) {
            $tab_internal_attributes += [
                "internal_tab_{$i}_order2" => '表示順',
                "internal_tab_{$i}_memo2" => '説明',
            ];
        }

        $tab_external_attributes = [];
        for ($i = 1; $i <= 30; $i++) {
            $tab_external_attributes += [
                "external_tab_{$i}_order2" => '表示順',
                "external_tab_{$i}_memo2" => '説明',
            ];
        }

        $tab_another_attributes = [];
        for ($i = 1; $i <= 30; $i++) {
            $tab_another_attributes += [
                "another_tab_{$i}_order2" => '表示順',
                "another_tab_{$i}_memo2" => '説明',
            ];
        }

        $attributes = [
            'main' => '施設メイン画像',
            'title' => 'タイトル',
            'caption' => '本文',
            'sub_1' => '施設サブ画像1',
            'sub_2' => '施設サブ画像2',
            'sub_3' => '施設サブ画像3',
            'sub_4' => '施設サブ画像4',
            'speciality_1' => 'こだわり1',
            'speciality_2' => 'こだわり2',
            'speciality_3' => 'こだわり3',
            'speciality_4' => 'こだわり4',
            'speciality_1_title' => 'こだわり1のタイトル',
            'speciality_2_title' => 'こだわり2のタイトル',
            'speciality_3_title' => 'こだわり3のタイトル',
            'speciality_4_title' => 'こだわり4のタイトル',
            'speciality_1_caption' => 'こだわり1の本文',
            'speciality_2_caption' => 'こだわり2の本文',
            'speciality_3_caption' => 'こだわり3の本文',
            'speciality_4_caption' => 'こだわり4の本文',
            'map_url' => '地図・アクセスURL',
            'interview_1' => 'インタビュー画像',
            'interview_1_title' => 'インタビュータイトル',
            'interview_1_caption' => 'インタビュー本文',
            'interview.*.question' => 'インタビュー質問',
            'interview.*.answer' => 'インタビュー回答',
            'interview_new.*.question' => 'インタビュー質問',
            'interview_new.*.answer' => 'インタビュー回答',
            'tab_1_order1' => 'タブの表示順',
            'tab_2_order1' => 'タブの表示順',
            'tab_3_order1' => 'タブの表示順',
            'tab_4_order1' => 'タブの表示順',
            'tab_5_order1' => 'タブの表示順',
            'staff_1' => 'スタッフ画像',
            'staff_1_name' => 'スタッフ名',
            'staff_1_career' => '経歴',
            'staff_1_memo' => 'コメント',
        ];
        $attributes = array_merge($attributes, $tab_facility_attributes);
        $attributes = array_merge($attributes, $tab_staff_attributes);
        $attributes = array_merge($attributes, $tab_internal_attributes);
        $attributes = array_merge($attributes, $tab_external_attributes);
        $attributes = array_merge($attributes, $tab_another_attributes);
        return $attributes;
    }

    public function messages()
    {
        return [
            'status.in' => '「状態」を正しく選択してください。',
        ];
    }
}
