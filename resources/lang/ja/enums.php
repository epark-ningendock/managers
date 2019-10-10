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
use App\Enums\HplinkContractType;
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
        Authority::ADMIN => 'システム管理者',
        Authority::MEMBER => 'メンバー',
        Authority::EXTERNAL_STAFF => '外部スタッフ',
        Authority::CONTRACT_STAFF => '契約管理者'
    ],

    StaffStatus::class => [
        StaffStatus::VALID => '有効',
        StaffStatus::INVALID => '無効',
        StaffStatus::DELETED => '削除'
    ],

    Permission::class => [
        Permission::NONE => '不可',
        Permission::VIEW => '閲覧',
        Permission::EDIT => '編集',
        Permission::UPLOAD => 'アップロード',
    ],

    Status::class => [
        Status::VALID => '有効',
        Status::DELETED => '削除'
    ],

    HospitalEnums::class => [
        HospitalEnums::PRIVATE => '非公開',
        HospitalEnums::PUBLIC => '公開中',
        HospitalEnums::DELETE => '削除',
    ],

    WebReception::class => [
        WebReception::NOT_ACCEPT => '非公開',
        WebReception::ACCEPT => '公開'
    ],

    CalendarDisplay::class => [
        CalendarDisplay::HIDE => 'カレンダー非表示',
        CalendarDisplay::SHOW => 'カレンダー表示'
    ],

    ReservationStatus::class => [
        ReservationStatus::PENDING => '仮受付',
        ReservationStatus::RECEPTION_COMPLETED => '受付確定',
        ReservationStatus::COMPLETED => '受診完了',
        ReservationStatus::CANCELLED => 'キャンセル'
    ],

    PaymentStatus::class => [
        PaymentStatus::TEMPORARY_SALE => '仮受付',
        PaymentStatus::ACTUAL_SALE => '実売上',
        PaymentStatus::CANCELLATION => '取消',
        PaymentStatus::ERROR => 'エラー'
    ],

    Gender::class =>[
        Gender::MALE => '男性',
        Gender::FEMALE => '女性'
    ],

    TerminalType::class => [
        TerminalType::HOSPITAL => '院内',
        TerminalType::PC => 'PC',
        TerminalType::SMART_PHONE => 'スマホ',
        TerminalType::PHONE_RESERVATION_API => '電話予約(API）',
        TerminalType::PHONE_RESERVATION_PPC => '電話予約(PPC)'
    ],

    CourseImageType::class => [
        CourseImageType::MAIN => '検査コースメイン',
        CourseImageType::PC => '受診の流れメイン（PC）',
        CourseImageType::SP => '受診の流れメイン（SP）',
    ],

    BillingStatus::class => [
        BillingStatus::UNCONFIRMED => '未確認',
        BillingStatus::CHECKING => '確認中',
        BillingStatus::CONFIRMED => '確認済',
        BillingStatus::CONFIRM => '確定',
    ],

    HplinkContractType::class => [
        HplinkContractType::NONE => '無し',
        HplinkContractType::PAY_PER_USE => '従量課金',
        HplinkContractType::MONTHLY_SUBSCRIPTION => '月額固定',
    ],
];
