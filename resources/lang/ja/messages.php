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

    'created'                              => ':nameを登録しました。',
    'updated'                              => ':nameを更新しました。',
    'updated_common'                       => '更新しました。',
    'remove'                               => '削除する',
    'deleted'                              => ':nameの削除が完了しました。',
    'sent'                                 => ':mailを送信しました。',
    'operation'                            => '操作対象に指定',
    'hospital_staff_update_passoword'      => '医療機関スタッフのパスワードを更新しました',
    'token_expired'                        => 'トークンが期限切れです。再送信してください',
    'hospital_staff_does_not_exist'        => 'この医療機関スタッフは存在しません。',
    'incorrect_token'                      => '不正なトークンです。',
    'not_correct'                          => ':name正しくない',
    'restored'                             => ':nameの復元が完了しました。',
    'invalid_format'                       => ':name 無効な形式',
    'staff_create_error'                   => '登録の際にエラーが発生しました。',
    'create_error'                         => 'エラーが発生しました。',
    'update_error'                         => 'エラーが発生しました。',
    'model_changed_error'                  => '更新中にデータ変更されました。',
    'select_hospital_confirmation'         => 'この医療機関を操作します。<br/>よろしいですか？',
    'delete_confirmation'                  => '本当に削除してもよろしいですか？<br/>この処理は取り消せません。',
    'delete_popup_title'                   => '確認を削除',
    'delete_image_popup_content'           => 'この画像を削除してもよろしいですか？',
    'delete_popup_content'                 => 'これを削除してよろしいです :name？',
    'course_delete_popup_content'          => 'この分類を削除します。よろしいですか？',
    'classification_delete_popup_content'  => 'この分類を削除します。よろしいですか？',
    'classification_restore_popup_content' => 'この分類を復元します。よろしいですか？',
    'classification_sort_updated'          => '分類レコードの順序が更新されました。',
    'invalid_classification_id'            => '分類IDが存在しません',
    'no_record'                            => 'レコードがありません',
    'hospital_image_update'                => '分類レコードの順序が更新されました。',
    'major_classification' => [
        'child_exist_error_on_delete'      => '中分類レコードがあります。 削除できません。',
    ],
    'middle_classification' => [
        'child_exist_error_on_delete'      => '小分類レコードがあります。 削除できません。',
        'parent_deleted_error_on_restore'  => '親の大分類レコードが削除されています。 復元できません。',
    ],
    'minor_classification' => [
        'parent_deleted_error_on_restore'  => '親の中分類レコードが削除されています。 復元できません。',
    ],
    'invalid_course_id'                    => 'コースIDが存在しません',
    'course_sort_updated'                  => '並べ替えが完了しました。',
    'names' => [
        'customers'                        => '受診者情報管理',
        'customer'                         => '顧客',
        'staff'                            => 'スタッフ情報',
        'hospital_staff'                   => '医療機関スタッフ',
        'hospital'                         => '医療機関',
        'facilities'                       => '設備',
        'classification'                   => '分類',
        'contractor'                       => '請負業者',
        'policy_holder'                    => '保険契約者',
        'representative_name_kana'         => '代表名（ふりがな',
        'representative_name'              => '代表名',
        'hospital_categories'              => '医療機関画像',
        'hospital_interview'               => 'インタビュー',
        'classifications' => [
            'major'                        => '大分類',
            'password'                     => 'パスワード',
            'current_password'             => '現在のパスワード',
            'middle'                       => '中分類',
            'minor'                        => '小分類'
        ],
        'course'                           => '検査コース',
        'course_image'                     => '検査コース画像',
        'calendar'                         => 'カレンダー',
        'email_template'                   => 'メールテンプレート',
        'calendar_setting'                 => 'カレンダー管理',
        'hospital_email_setting'           => '受付メール設定',
        'holiday_setting'                  => '休日設定',
        'attetion_information'             => '医療機関こだわり情報',
        'billing' => '請求',
    ],
    'course'                               => '検査コース',
    'name'                                 => 'お名前',
    'registration_card_number'             => '診察券番号',
    'family_name'                          => '姓',
    'first_name'                           => '名',
    'family_name_kana'                     => 'セイ',
    'first_name_kana'                      => 'メイ',
    'tel'                                  => '電話番号',
    'birthday'                             => '生年月日',
    'email'                                => 'メールアドレス',
    'sex'                                  => '性別',
    'updated_at'                           => '更新日',
    'location'                             => '所在地',
    'contact_information'                  => '連絡先',
    'business_hours'                       => '休診日・診療時間',
    'address'                              => '住所',
    'building_name'                        => '建物名',
    'district_code'                        => '市町村区',
    'streetview_url'                       => 'ストリートビューURL',
    'paycall'                              => 'ペイパーコール',
    'rail'                                 => '路線',
    'station'                              => '駅',
    'access'                               => '駅からのアクセス',
    'start'                                => '開始',
    'end'                                  => '終わり',
    'mon'                                  => '月曜',
    'tue'                                  => '火曜',
    'wed'                                  => '水曜',
    'thu'                                  => '木曜',
    'fri'                                  => '金曜',
    'sat'                                  => '土曜',
    'sun'                                  => '日曜',
    'consultation_note'                    => '休診補足',
    'latitude'                             => '北緯',
    'longitude'                            => '東経',
    'medical_examination_system_id'        => '検診システムID',
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
    'postcode'                             => '郵便番号',
    'send_mail'                            => 'メール送信',
    'memo'                                 => '顧客メモ',
    'remarks'                              => '備考',
    'examination_system_name'              => '検診システム名',
    'kenshin_sys_hospital_id'              => '健診システム医療機関ID',
    'reservation_memo'                     => '次回受付メモ',
    'claim_count'                          => 'クレーム数',
    'recall_count'                         => 'リコール数',
    'reservation_date'                     => '受診日',
    'course_name'                          => '検査コース名',
    'reservation_status'                   => 'ステータス',
    'consultation_ticket_number'           => '診察券番号',
    'male'                                 => '男性',
    'female'                               => '女性',
    'registration'                         => '登録',
    'prefectures'                          => '都道府県',
    'update'                               => '更新',
    'sending_email'                        => '送信...',
    'close'                                => '閉じる',
    'calendar'                             => 'カレンダー',
    'email_template'                       => 'メールテンプレート',
    'calendar_setting'                     => 'カレンダー管理',
    'mails' => [
        'reset_passoword' => 'パスワードリセットメール',
        'registered' => 'スタッフ登録メール',
        'customer' => '顧客メール',
        'sent_datetime' => '送信日時',
        'title' => 'メール件名'
    ],
    'time_invalid'                         => '時間が無効です',
    'option_name'                          => 'オプション名',
    'option_description'                   => 'オプションの説明',
    'price'                                => '価格',
    'tax_classification'                   => '税区分',
    'invalid_option_id'                    => '無効なオプションID',
    'option_sorting_updated'               => 'オプションソートの更新',
    'need_to_login'                        => 'あなたは最初にログインする必要があります',
    'kana'                                 => 'よみ',
    'reservation' => [
        'accept_confirmation'              => '予約を確定します',
        'cancel_confirmation'              => '予約をキャンセルしますか？',
        'complete_confirmation'            => '受診完了します',
        'accept_success'                   => '予約は承認されました。',
        'accept_error'                     => 'エラーが発生しました。',
        'cancel_success'                   => '予約がキャンセルされました。',
        'cancel_error'                     => 'エラーが発生しました。',
        'complete_success'                 => '予約は完了しました。',
        'complete_error'                   => 'エラーが発生しました。',
        'status_update_success'            => '予約ステータスが更新されました。',
        'update_success'                   => '予約が更新されました。',
        'status_update_error'              => 'エラーが発生しました。',
        'invalid_reservation_status'       => '無効な操作。',
        'not_reservable'                   => '予約可能な日付ではありません。',
        'limit_exceed'                     => '予約可能件数を超えています。'
    ],
    'image_category' => [
        'update_success'                   => '画像が更新されました。',
    ],
    'address1'                             => '市区郡',
    'address2'                             => 'それ以降の住所',
    'contract_saved'                       => '契約情報が保存されました。',
    'email-template-limit-exceed'          => 'テンプレートの数が最大になっているため、新規作成を実行できませんでした。',
    'invalid_email'                        => '正しいメールアドレスの書式でご入力ください',
    'billing_email_flg'                    => '請求メールの設定',
    'billing_email_flg_receive'            => '受け取る',
    'billing_email_flg_not_accept'         => '受け取らない',
    'billing_email1'                       => '請求メール受信アドレス1',
    'billing_email2'                       => '請求メール受信アドレス2',
    'billing_email3'                       => '請求メール受信アドレス3',
    'billing_fax_number'                   => '請求メール受信fax番号',
    'billing' => '請求',
    'property_no'                          => '物件番号',
    'customer_no'                          => '顧客番号',
    'contractor_name'                      => '契約者名',
    'contractor_name_kana'                 => '契約者名（フリガナ）',
    'application_date'                     => '申込日',
    'billing_start_date'                   => '課金開始日',
    'cancellation_date'                    => '解約日',
    'representative_name'                  => '代表者名',
    'representative_name_kana'             => '代表者名（フリガナ）',
    'hospital_name'                        => '屋号',
    'fax_no'                               => 'FAX番号',
    'plan_code'                            => 'プランコード',
    'plan_name'                            => 'プラン名',
    'service_start_date'                   => 'サービス開始日',
    'service_end_date'                     => 'サービス終了日',

    'integrate'                            => '統合対象',
    'action'                               => '操作',
    'display_switching'                    => '表示切替',
    'perform_identification'               => '名寄せを実行する',
    'integration-success'                  => '名寄せが完了しました。',
    'integration-error'                    => 'エラーが発生しました。',
    'input-required'                       => '入力内容をご確認ください。',

    'for_api' => [
        'success'                           => '正常に受け付けました',
    ],
];
