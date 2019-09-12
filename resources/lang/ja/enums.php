<?php
/**
 * Created by PhpStorm.
 * User: thanhtetaung
 * Date: 2019-04-02
 * Time: 23:19
 */

use \App\Enums\Authority;
use App\Enums\BillingStatus;
use \App\Enums\CourseImageType;
use App\Enums\HospitalEnums;
use App\Enums\StaffStatus;
use App\Enums\Permission;
use App\Enums\Status;
use App\Enums\WebReception;
use App\Enums\CalendarDisplay;
use App\Enums\ReservationStatus;
use App\Enums\PaymentStatus;
use App\Enums\Gender;
use App\Enums\TerminalType;

return [
    Authority::class => [
        Authority::Admin => 'システム管理者',
        Authority::Member => 'メンバー',
        Authority::ExternalStaff => '外部スタッフ',
        Authority::ContractStaff => '契約管理者'
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
        WebReception::NotAccept => '非公開',
        WebReception::Accept => '公開'
    ],

    CalendarDisplay::class => [
        CalendarDisplay::Hide => 'カレンダー非表示',
        CalendarDisplay::Show => 'カレンダー表示'
    ],

    ReservationStatus::class => [
        ReservationStatus::Pending => '仮受付',
        ReservationStatus::ReceptionCompleted => '受付完了',
        ReservationStatus::Completed => '完了',
        ReservationStatus::Cancelled => 'キャンセル'
    ],

    PaymentStatus::class => [
        PaymentStatus::TEMPORARY_SALE => '仮受付',
        PaymentStatus::ACTUAL_SALE => '実売上',
        PaymentStatus::CANCELLATION => '取消',
        PaymentStatus::ERROR => 'エラー'
    ],

    Gender::class =>[
        Gender::Male => '男性',
        Gender::Female => '女性'
    ],

    TerminalType::class => [
        TerminalType::Hospital => '院内',
        TerminalType::PC => 'PC',
        TerminalType::SmartPhone => 'スマホ',
        TerminalType::PhoneReservationAPI => '電話予約(API）',
        TerminalType::PhoneReservationPPC => '電話予約(PPC)'
    ],

    CourseImageType::class => [
        CourseImageType::Main => '検査コースメイン',
        CourseImageType::Pc => '受診の流れメイン（PC）',
        CourseImageType::Sp => '受診の流れメイン（SP）',
    ],

    BillingStatus::class => [
        BillingStatus::Unconfirmed => '未確認',
        BillingStatus::Checking => '確認中',
        BillingStatus::Confirmed => '確認済',
        BillingStatus::Confirm => '確定',
    ]
];
