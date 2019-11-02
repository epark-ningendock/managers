<?php

return [
    //pv集計日数
    'pv_aggregate_day' => 7,
    //仮受付経過日数
    'pending_passed_day' => 2,
    //サービスID
    'service_id' => 12,
    // TAK健診システムAPI連携機能
    'tak_api' => [
        'development' => [
            'api_url'       => 'https://api-proxy-test-1.ss.sevenbank.co.jp/real_transfer_8-3/v1/transfer',
            'partner_code'  => '1810072185',
            'hash_key'      => '1234docknets',
            'subscription_key' => '52tow9x8kp5-1u7oq0ijn83m3-a80hl6s',
            'ip' => '',
        ],
        'production' => [
            'api_url'       => 'https://api-proxy-1.ss.sevenbank.co.jp/real_transfer_8/v1/transfer',
            'partner_code'  => '1810072185',
            'hash_key'      => '1234docknets',
            'subscription_key' => 'f9wb38z6ek1-3gr80fu04kw32-tl93qh4',
            'ip' => '',
        ],
    ],

    // アイテック阪急阪神
    'itec_api' => [
        'development' => [
            'api_url'       => 'https://api-proxy-test-1.ss.sevenbank.co.jp/real_transfer_8-3/v1/transfer',
            'partner_code'  => '0710065129',
            'hash_key'      => '1234docknets',
            'subscription_key' => '71ecf1z9ce2-4c3e92y181w93-5b91d08',
            'ip' => '',
        ],
        'production' => [
            'api_url'       => 'https://api-proxy-1.ss.sevenbank.co.jp/real_transfer_8/v1/transfer',
            'partner_code'  => '0710065129',
            'hash_key'      => '1234docknets',
            'subscription_key' => '28er11o7cr1-4c7u9qh171w91-ek92d78',
            'ip' => '',
        ],
    ],
];

