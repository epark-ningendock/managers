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
            'api_url'       => 'http://153.122.82.23/kenshinwebeparkapi/',
        ],
    ],

    // アイテック阪急阪神
    'itec_api' => [
        'stg' => [
            'api_url'       => 'http://153.126.178.64/epark/v1/',
        ],
        'prod' => [
            'api_url'       => 'http://153.126.178.64/epark/v1/',
        ],
    ],
];

