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
    // システムエラー
    'sys_error' => [
        'message' => [
            'errorDB' => [
                'code' => '90001',
                'description' => 'データベースシステムエラー',
            ],
         ],
    ],
];
