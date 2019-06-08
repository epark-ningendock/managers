<?php
/**
 * Created by PhpStorm.
 * User: thanhtetaung
 * Date: 2019-04-02
 * Time: 23:19
 */

use \App\Enums\Authority;
use App\Enums\HospitalEnums;
use App\Enums\StaffStatus;
use App\Enums\Permission;
use App\Enums\Status;
use App\Enums\WebReception;
use App\Enums\CalendarDisplay;

return [
    Authority::class => [
        Authority::Admin => 'システム管理者',
        Authority::Member => 'メンバー',
        Authority::ExternalStaff => '外部スタッフ'
    ],

    StaffStatus::class => [
        StaffStatus::Valid => '有効',
        StaffStatus::Invalid => '無効',
        StaffStatus::Deleted => '削除'
    ],

    Permission::class => [
        Permission::None => '不可',
        Permission::View => '閲覧',
        Permission::Edit => '編集',
        Permission::Upload => 'アップロード',
    ],

    Status::class => [
        Status::Valid => '有効',
        Status::Deleted => '削除'
    ],

    HospitalEnums::class => [
        HospitalEnums::Private => '非公開',
        HospitalEnums::Public => '公開中',
        HospitalEnums::Delete => '削除',
    ],

    WebReception::class => [
        WebReception::NotAccept => '受け付けない',
        WebReception::Accept => '受け付ける'
    ],

    CalendarDisplay::class => [
        CalendarDisplay::Hide => '非表示',
        CalendarDisplay::Show => '表示'
    ]
];
