<?php

return [
    // ログイン情報API
    'member_login_info_api' => [
        'message' => [
            'success' => [
                'code' => '00000',
                'description' => '正常に受け付けました',
            ],
            'errorEparkMemberId' => [
                'code' => '40001',
                'description' => '入力値フォーマットエラー。EPARKメンバーID',
            ],
            'errorMailInfoDelivery' => [
                'code' => '40002',
                'description' => '入力値フォーマットエラー。メール情報配信',
            ],
            'errorNickUse' => [
                'code' => '40003',
                'description' => '入力値フォーマットエラー。ニックネーム使用',
            ],
            'errorContact' => [
                'code' => '40004',
                'description' => '入力値フォーマットエラー。連絡先登録',
            ],
            'errorContactName' => [
                'code' => '40005',
                'description' => '入力値フォーマットエラー。連絡先名称',
            ],
            'errorStatus' => [
                'code' => '40006',
                'description' => '入力値フォーマットエラー。状態',
            ],
            'errorNotExistInfo' => [
                'code' => '40010',
                'description' => 'EPARK会員ログイン情報が見つかりません。',
            ],
        ],
     ],
    // 検討中リストAPI
    'consideration_list_api' => [
        'message' => [
            'success' => [
                'code' => '00000',
                'description' => '正常に受け付けました',
            ],
            'errorEparkMemberId' => [
                'code' => '40001',
                'description' => '入力値フォーマットエラー。EPARKメンバーID',
            ],
            'errorHospitalId' => [
                'code' => '40011',
                'description' => '入力値フォーマットエラー。医療機関ID',
            ],
            'errorCourseId' => [
                'code' => '40012',
                'description' => '入力値フォーマットエラー。検査コースID',
            ],
            'errorDisplayKbn' => [
                'code' => '40013',
                'description' => '入力値フォーマットエラー。表示区分',
            ],
            'errorStatus' => [
                'code' => '40006',
                'description' => '入力値フォーマットエラー。状態',
            ],
            'errorNotExistInfo' => [
                'code' => '40010',
                'description' => '検討中リストが見つかりません。',
            ],
        ],
    ],
    // 検索API
    'search_api' => [
        'message' => [
            'success' => [
                'status' => 0,
                'http_status' => '200',
                'description' => '正常に受け付けました'
            ],
            'system_error_db' => [
                'status' => 1,
                'http_status' => 400,
                'error_no' => '01',
                'detail_no' => '01',
                'description' => 'データベースエラー: '
            ],
            'system_error_api' => [
                'status' => 1,
                'http_status' => 400,
                'error_no' => '01',
                'detail_no' => '02',
                'description' => 'APIエラー: '
            ],
            'required_error' => [
                'status' => 1,
                'http_status' => 400,
                'error_no' => '03',
                'detail_no' => '01',
                'description' => '必須項目未設定: '
            ],
            'required_with_cond_error' => [
                'status' => 1,
                'http_status' => 400,
                'error_no' => '04',
                'detail_no' => '01',
                'description' => '条件付き必須項目未設定: '
            ],
            'data_format_error' => [
                'status' => 1,
                'http_status' => 400,
                'error_no' => '05',
                'detail_no' => '01',
                'description' => 'データ型エラー: '
            ],
            'data_type_error' => [
                'status' => 1,
                'http_status' => 400,
                'error_no' => '06',
                'detail_no' => '01',
                'description' => 'データ種別エラー: '
            ],
            'data_range_error' => [
                'status' => 1,
                'http_status' => 400,
                'error_no' => '07',
                'detail_no' => '01',
                'description' => 'データ範囲エラー: '
            ],
            'data_empty_error' => [
                'status' => 1,
                'http_status' => 400,
                'error_no' => '09',
                'detail_no' => '01',
                'description' => 'データ取得エラー: '
            ],
            'data_length_error' => [
                'status' => 1,
                'http_status' => 400,
                'error_no' => '10',
                'detail_no' => '01',
                'description' => '指定外長さデータエラー: '
            ],
            'date_range_error' => [
                'status' => 1,
                'http_status' => 400,
                'error_no' => '11',
                'detail_no' => '01',
                'description' => '日付範囲エラー: '
            ]
        ]
    ],
    // コース情報通知API
    'course_info_notification_api' => [
        'message' => [
            'success' => [
                'code' => '00000',
                'description' => '正常に受け付けました',
            ],
            'errorValidationId' => [
                'code' => 'D_50001',
                'description' => '入力値バリデーションエラー。',
            ],
            'errorSubscriptionKeyId' => [
                'code' => 'D_50002',
                'description' => 'サブスクリプションキーエラー。',
            ],
            'errorPartnerCdId' => [
                'code' => 'D_50003',
                'description' => '提携先コードエラー。',
            ],
            'errorAgeKisanKbn' => [
                'code' => 'D_50004',
                'description' => '年齢起算区分エラー。',
            ],
            'errorSex' => [
                'code' => 'D_50005',
                'description' => '性別エラー。',
            ],
            'errorHonninKbn' => [
                'code' => 'D_50006',
                'description' => '本人区分エラー。',
            ],
            'errorYuusenKbn' => [
                'code' => 'D_50007',
                'description' => '優先区分エラー。',
            ],
            'errorYoyakuStatusKbn' => [
                'code' => 'D_50008',
                'description' => '予約ステータス区分エラー。',
            ],
            'errorDataNotFound' => [
                'code' => 'D_50009',
                'description' => '該当データなしエラー。',
            ],
        ],
    ],
    // システムエラー
    'sys_error' => [
        'message' => [
            'errorDB' => [
                'code' => '90001',
                'description' => 'データベースシステムエラー',
            ],
        ],
    ],
    // 予期せぬエラー
    'unexpected_error' => [
        'message' => [
            'code' => 'D_99999',
            'description' => 'その他エラー',
        ],
    ],
];