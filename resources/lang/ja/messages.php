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
    'updated' => ':nameを更新しました。',
    'remove' => '削除する',
    'deleted' => ':nameを削除しました。',
    'sent' => ':mailを送信しました。',
    'hospital_staff_update_passoword' => '医療機関スタッフのパスワードを更新しました',
    'token_expired' => 'トークンが期限切れです。再送信してください',
    'hospital_staff_does_not_exist' => 'この医療機関スタッフは存在しません。',
    'incorrect_token' => '不正なトークンです。',
    'not_correct' => ':name正しくない',
    'restored' => ':nameが復元されます。',
    'invalid_format'                       => ':name 無効な形式',
    'staff_create_error' => '正しく登録されませんでした。',
    'create_error' => 'エラーが発生しました。',
    'update_error' => 'エラーが発生しました。',
    'delete_confirmation' => '本当に削除しても宜しいですか？<br/>この処理は取り消せません。',
    'delete_popup_title' => '確認を削除',
    'delete_popup_content' => 'これを削除してよろしいです :name？',
    'classification_delete_popup_content' => 'この分類を削除します。よろしいですか？',
    'classification_restore_popup_content' => 'この分類を復元します。よろしいですか？',
    'classification_sort_updated' => '分類レコードの順序が更新されました。',
    'invalid_classification_id' => '分類IDが存在しません',
    'no_record' => 'レコードがありません',
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
    'invalid_course_id' => 'コースIDが存在しません',
    'course_sort_updated' => '講座記録の順序が更新されました。',
    'names' => [
        'customers'       => 'お客様',
        'customer'        => '顧客',
        'staff' => 'スタッフ情報',
        'hospital_staff' => '医療機関スタッフ',
        'hospital' => '医療機関',
        'facilities' => '設備',
        'classification' => '分類',
        'classifications' => [
            'major' => '大分類',
        'password' => 'パスワード',
        'current_password' => '現在のパスワード',
            'middle' => '中分類',
            'minor' => '小分類'
        ],
        'course' => '検査コース',
        'calendar' => 'カレンダー',
        'email_template' => 'メールテンプレート',
        'calendar_setting' => 'カレンダー管理',
        'reception_email_setting' => '受付メール設定'
    ],
    'course'                               => '検査コース',
    'name'                                 => 'お名前',
    'registration_card_number'             => '診察券番号',
    'tel'                                  => '電話番号',
    'birthday'                             => '生年月日',
    'email'                                => 'メールアドレス',
    'sex'                                  => '性別',
    'updated_at'                           => '更新日',
    'search'                               => '検索',
    'clear_search'                         => '検索をクリア',
    'create_new'                           => '新規作成',
    'bulk_registration'                    => '一括登録',
    'upload'                               => 'アップロードする',
    'file_selection'                       => 'ファイル選択',
    'phone_number'                         => '電話番号',
    'gender'                               => '性別',
    'registration_form'                    => '登録形態',
    'edit'                                 => '編集',
    'delete'                               => '削除',
    'basic_information'                    => '基本情報',
    'accepted_guidance_history'            => '受付案内履歴',
    'name_identification'                  => '名寄せ',
    'customer_id'                          => '顧客ID',
    'name_kana'                            => 'お名前（かな）',
    'postcode'                             => '住所',
    'send_mail'                            => 'メールを送る',
    'memo'                                 => '顧客メモ',
    'reservation_memo'                     => '次回受付メモ',
    'claim_count'                          => 'クレーム数',
    'recall_count'                         => 'リコール数',
    'reservation_date'                     => '予約日（受診日）',
    'course_name'                          => '検査コース名',
    'reservation_status'                   => 'ステータス',
    'consultation_ticket_number'           => '診察券番号',
    'male'                                 => '男性',
    'female'                               => '女性',
    'registration'                         => '登録',
    'prefectures'                          => '都道府県',
    'update'                               => '更新',
    'send_email'                           => 'メールを送る',
    'close'                                => '閉じる',
    'calendar'                             => 'カレンダー',
    'email_template'                       => 'メールテンプレート',
    'calendar_setting'                     => 'カレンダー管理',
    'mails' => [
        'reset_passoword' => '医療機関スタッフパスワードリセットメール',
        'registered' => '医療機関スタッフ登録メール',
    ],
    'option_name' => 'オプション名',
    'option_description' => 'オプションの説明',
    'price' => '価格',
    'tax_classification' => '税区分',
    'invalid_option_id' => '無効なオプションID',
    'option_sorting_updated' => 'オプションソートの更新',
    'need_to_login' => 'あなたは最初にログインする必要があります'
];
