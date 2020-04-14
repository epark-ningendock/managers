<?php

return [
    //pv集計日数
    'pv_aggregate_day' => 7,
    //仮受付経過日数
    'pending_passed_day' => 2,
    //サービスID
    'service_id' => 12,
    // 健診システムID
    'medical_exam_sys_id' => [
        'tak' => 1,
        'itec' => 2,
    ],
    // TAK健診システムAPI連携機能
    'tak_api' => [
        'stg' => [
            'api_url'       => 'http://153.122.82.23/kenshinwebeparkapi/',
        ],
        'prod' => [
            'api_url'       => 'http://153.122.82.23/kenshinwebeparkapi/c',
        ],
    ],

    // アイテック阪急阪神
    'itec_api' => [
        'stg' => [
            'api_url'       => 'https://api-proxy-test-1.ss.sevenbank.co.jp/real_transfer_8-3/v1/transfer',
        ],
        'prod' => [
            'api_url'       => 'https://api-proxy-1.ss.sevenbank.co.jp/real_transfer_8/v1/transfer',
        ],
    ],
];

