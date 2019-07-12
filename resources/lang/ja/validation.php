<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages.
    |
    */
    'accepted'        => ':attributeを承認してください。',
    'active_url'      => ':attributeは、有効なURLではありません。',
    'after'           => ':attributeには、:dateより後の日付を指定してください。',
    'after_or_equal'  => ':attributeには、:date以降の日付を指定してください。',
    'alpha'           => ':attributeには、アルファベッドのみ使用できます。',
    'alpha_dash'      => ":attributeには、英数字('A-Z','a-z','0-9')とハイフンと下線('-','_')が使用できます。",
    'alpha_num'       => ":attributeには、英数字('A-Z','a-z','0-9')が使用できます。",
    'array'           => ':attributeには、配列を指定してください。',
    'before'          => ':attributeには、:dateより前の日付を指定してください。',
    'before_or_equal' => ':attributeには、:date以前の日付を指定してください。',
    'between'         => [
        'numeric' => ':attributeには、:minから、:maxまでの数字を指定してください。',
        'file'    => ':attributeには、:min KBから:max KBまでのサイズのファイルを指定してください。',
        'string'  => ':attributeは、:min文字から:max文字にしてください。',
        'array'   => ':attributeの項目は、:min個から:max個にしてください。',
    ],
    'boolean'              => ":attributeには、'true'か'false'を指定してください。",
    'confirmed'            => ':attributeと:attribute確認が一致しません。',
    'date'                 => ':attributeは、正しい日付ではありません。',
    'date_equals'          => ':attributeは:dateに等しい日付でなければなりません。',
    'date_format'          => ":attributeの形式は、':format'と合いません。",
    'different'            => ':attributeと:otherには、異なるものを指定してください。',
    'digits'               => ':attributeは、:digits桁にしてください。',
    'digits_between'       => ':attributeは、:min桁から:max桁にしてください。',
    'dimensions'           => ':attributeの画像サイズが無効です',
    'distinct'             => ':attributeの値が重複しています。',
    'email'                => ':attributeは、有効なメールアドレス形式で指定してください。',
    'exists'               => '選択された:attributeは、有効ではありません。',
    'file'                 => ':attributeはファイルでなければいけません。',
    'filled'               => ':attributeは必須です。',
    'gt'                   => [
        'numeric' => ':attributeは、:valueより大きくなければなりません。',
        'file'    => ':attributeは、:value KBより大きくなければなりません。',
        'string'  => ':attributeは、:value文字より大きくなければなりません。',
        'array'   => ':attributeの項目数は、:value個より大きくなければなりません。',
    ],
    'gte'                  => [
        'numeric' => ':attributeは、:value以上でなければなりません。',
        'file'    => ':attributeは、:value KB以上でなければなりません。',
        'string'  => ':attributeは、:value文字以上でなければなりません。',
        'array'   => ':attributeの項目数は、:value個以上でなければなりません。',
    ],
    'image'                => ':attributeには、画像を指定してください。',
    'in'                   => '選択された:attributeは、有効ではありません。',
    'in_array'             => ':attributeが:otherに存在しません。',
    'integer'              => ':attributeには、整数を指定してください。',
    'ip'                   => ':attributeには、有効なIPアドレスを指定してください。',
    'ipv4'                 => ':attributeはIPv4アドレスを指定してください。',
    'ipv6'                 => ':attributeはIPv6アドレスを指定してください。',
    'json'                 => ':attributeには、有効なJSON文字列を指定してください。',
    'lt'                   => [
        'numeric' => ':attributeは、:valueより小さくなければなりません。',
        'file'    => ':attributeは、:value KBより小さくなければなりません。',
        'string'  => ':attributeは、:value文字より小さくなければなりません。',
        'array'   => ':attributeの項目数は、:value個より小さくなければなりません。',
    ],
    'lte'                  => [
        'numeric' => ':attributeは、:value以下でなければなりません。',
        'file'    => ':attributeは、:value KB以下でなければなりません。',
        'string'  => ':attributeは、:value文字以下でなければなりません。',
        'array'   => ':attributeの項目数は、:value個以下でなければなりません。',
    ],
    'max'                  => [
        'numeric' => ':attributeには、:max以下の数字を指定してください。',
        'file'    => ':attributeには、:max KB以下のファイルを指定してください。',
        'string'  => ':attributeは、:max文字以下にしてください。',
        'array'   => ':attributeの項目は、:max個以下にしてください。',
    ],
    'mimes'                => ':attributeには、:valuesタイプのファイルを指定してください。',
    'mimetypes'            => ':attributeには、:valuesタイプのファイルを指定してください。',
    'min'                  => [
        'numeric' => ':attributeには、:min以上の数字を指定してください。',
        'file'    => ':attributeには、:min KB以上のファイルを指定してください。',
        'string'  => ':attributeは、:min文字以上にしてください。',
        'array'   => ':attributeの項目は、:min個以上にしてください。',
    ],
    'not_in'               => '選択された:attributeは、有効ではありません。',
    'not_regex'            => ':attributeの形式が無効です。',
    'numeric'              => ':attributeには、数字を指定してください。',
    'present'              => ':attributeが存在している必要があります。',
    'regex'                => ':attributeには、有効な正規表現を指定してください。',
    'required'             => ':attributeは、必ず指定してください。',
    'required_if'          => ':otherが:valueの場合、:attributeを指定してください。',
    'required_unless'      => ':otherが:values以外の場合、:attributeを指定してください。',
    'required_with'        => ':valuesが指定されている場合、:attributeも指定してください。',
    'required_with_all'    => ':valuesが全て指定されている場合、:attributeも指定してください。',
    'required_without'     => ':valuesが指定されていない場合、:attributeを指定してください。',
    'required_without_all' => ':valuesが全て指定されていない場合、:attributeを指定してください。',
    'same'                 => ':attributeと:otherが一致しません。',
    'size'                 => [
        'numeric' => ':attributeには、:sizeを指定してください。',
        'file'    => ':attributeには、:size KBのファイルを指定してください。',
        'string'  => ':attributeは、:size文字にしてください。',
        'array'   => ':attributeの項目は、:size個にしてください。',
    ],
    'starts_with'          => ':attributeは、次のいずれかで始まる必要があります。:values',
    'string'               => ':attributeには、文字を指定してください。',
    'timezone'             => ':attributeには、有効なタイムゾーンを指定してください。',
    'unique'               => '指定の:attributeは既に使用されています。',
    'uploaded'             => 'アップロードに失敗しました。',
    'url'                  => ':attributeは、有効なURL形式で指定してください。',
    'uuid'                 => ':attributeは、有効なUUIDでなければなりません。',
    'enum_value'                => '入力した値:attributeのは無効です',
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */
    'custom' => [
        'login_id' => [
            'between' => 'スタッフ名は、8~50文字以内の英数記号（.-_@のみ利用可能）でご入力ください。',
            'regex' => 'スタッフ名は、8~50文字以内の英数記号（.-_@のみ利用可能）でご入力ください。'
        ],
        'password' => [
            'between' => 'パスワードは、8~50文字以内の半角英数字でご入力ください。',
            'alpha_num' => 'パスワードは、8~50文字以内の半角英数字でご入力ください。'
        ],
        'email' => [
            'email' => '正しいメールアドレスの書式でご入力ください。'
        ],
        'is_hospital' => [
            'required' => ':attributeは必須です'
        ],
        'is_staff' => [
            'required' => ':attributeは必須です'
        ],
        'is_item_category' => [
            'required' => ':attributeは必須です'
        ],
        'is_invoice' => [
            'required' => ':attributeは必須です'
        ],
        'is_pre_account' => [
            'required' => ':attributeは必須です'
        ]
    ],
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */
    'attributes' => [
        'name' => 'スタッフ名',
        'login_id' => 'ログインID',
        'email' => 'メールアドレス',
        'password' => 'パスワード',
        'password_confirmation' => 'パスワード確認',
        'status' => '状態',
        'is_hospital' => '医療機関管理',
        'is_staff' => 'スタッフ管理',
        'is_item_category' => '検査コース分類',
        'is_invoice' => '請求管理',
        'is_pre_account' => '事前決済管理',
        'classification' => '分類',
        'classification_ids' => '分類ID',
        'classification_type_id' => '分数種別',
        'classification_name' => '分類名',
        'major_classification_id' => '大分数',
        'middle_classification_id' => '中分数名',
        'is_icon' => 'アイコン表示区分',
        'icon_name' => 'アイコン表示分',
        'is_fregist' => '登録区分',
        'max_length' => 'テキスト長',
        'course_ids' => 'コースID',
        'course_name' => '検査コース名',
        'course_is_category' => 'コースの種別',
        'web_reception' => 'WEBの受付',
        'reception_start_day' => '受付時間の開始日',
        'reception_start_month' => '受付時間の開始月',
        'reception_end_day' => '受付時間の終了日',
        'reception_end_month' => '受付時間の終了月',
        'reception_acceptance_day' => '受付許可日の終了日',
        'reception_acceptance_month' => '受付許可日の終了月',
        'price' => '表示価格',
        'price_memo' => '手動設定金額',
        'course_is_pre_account' => '利用設定',
        'is_question' => '質問事項の利用',
        'confirm' => 'オプションの説明',
        'tax_classification' => '税区分',
        'calendar_name' => 'カレンダー名',
        'is_calendar_display' => 'カレンダー受付可否',
        'title' => '件名',
        'text' => '本文',
        'hospital_id' => '医療機関ID',
        // 受付メール設定系
        'in_hospital_email_reception_flg' => '院内受付メール受信フラグ',
        'in_hospital_confirmation_email_reception_flg' => '受付確定メール受信フラグ',
        'in_hospital_change_email_reception_flg' => '受付変更メール受信フラグ',
        'in_hospital_cancellation_email_reception_flg' => '受付キャンセルメール受信フラグ',
        'email_reception_flg' => '受付メール受信フラグ',
        'in_hospital_reception_email_flg' => '受付メール（院内）受信フラグ',
        'web_reception_email_flg' => '受付メール（web）受信フラグ',
        'reception_email1' => '受信メールアドレス1',
        'reception_email2' => '受信メールアドレス2',
        'reception_email3' => '受信メールアドレス3',
        'reception_email4' => '受信メールアドレス4',
        'reception_email5' => '受信メールアドレス5',
        'epark_in_hospital_reception_mail_flg' => '受付メール（院内）受信フラグ(epark)',
        'epark_web_reception_email_flg' => '受付メール（web）受信フラグ(epark)',
        'reservation_start_date' => '予約開始日',
        'reservation_end_date' => '予約終了日',
        'completed_start_date' => '受診開始日',
        'completed_end_date' => '受診終了日',
        'customer_name' => '受診者名',
        //Reservation
        'regular_price' => 'コース料金',
        'adjustment_price' => '調整額',
        'reservation_memo' => '受付・予約メモ',
        'registration_card_number' => '診察券番号',
        'tel' => '電話番号',
        'family_name_kana' => 'お名前 かな ( 姓 )',
        'first_name_kana' => 'お名前 かな ( 名 )',
        'family_name' => 'お名前 ( 姓 )',
        'first_name' => 'お名前 ( 名 )',
    ],
];
