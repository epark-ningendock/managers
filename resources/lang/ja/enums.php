<?php
/**
 * Created by PhpStorm.
 * User: thanhtetaung
 * Date: 2019-04-02
 * Time: 23:19
 */

use \App\Enums\Authority;
use App\Enums\Status;
use App\Enums\Permission;

return [
    Authority::class => [
        Authority::Admin => 'システム管理者',
        Authority::Member => 'メンバー',
        Authority::ExternalStaff => '外部スタッフ'
    ],

    Status::class => [
        Status::Valid => '有効',
        Status::Invalid => '無効',
        Status::Deleted => '削除'
    ],

    Permission::class => [
        Permission::None => '不可',
        Permission::View => '閲覧',
        Permission::Edit => '編集',
        Permission::Upload => 'アップロード',
    ]
];