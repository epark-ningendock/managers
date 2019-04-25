<?php
/**
 * Created by PhpStorm.
 * User: thanhtetaung
 * Date: 2019-04-03
 * Time: 21:23
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Messages Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the text and messages used by
    | the controller class and view. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'created' => ':nameが登録しました。',
    'updated' => ':nameが削除しました。',
    'deleted' => ':nameが変更しました。',
    'restored' => ':nameが復元されます。',
    'staff_create_error' => '正しく登録されませんでした。',
    'delete_confirmation' => '本当に削除しても宜しいですか？<br/>この処理は取り消せません。',
	'delete_popup_title' => '確認を削除',
	'delete_popup_content' => 'これを削除してよろしいです :name？',
    'classification_delete_popup_content' => 'この分類を削除します。よろしいですか？',
    'classification_restore_popup_content' => 'この分類を復元します。よろしいですか？',
    'major_classification' => [
        'child_exist_error_on_delete' => '中分類の記録があります。 操作は完了できません。'
    ],
    'middle_classification' => [
        'child_exist_error_on_delete' => '小分類レコードがあります。 操作は完了できません。',
        'parent_deleted_error_on_restore' => '親の大分類レコードが削除されます。 操作は完了できません。'
    ],
    'minor_classification' => [
        'parent_deleted_error_on_restore' => '親の中分類レコードが削除されます。 操作は完了できません。'
    ],
    'names' => [
        'staff' => 'スタッフ情報',
        'hospital_staff' => '病院スタッフ',
	    'hospital' => '医療機関',
	    'facilities' => '設備',
        'classifications' => [
            'major' => '大分類',
            'middle' => '中分類',
            'minor' => '小分類'
        ]
    ],
	'no_record' => 'レコードなし',
];