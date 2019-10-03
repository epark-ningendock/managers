<?php
/**
 * Created by PhpStorm.
 * User: thanhtetaung
 * Date: 2019-04-02
 * Time: 23:19
 */

use \App\Enums\Authority;
use App\Enums\BillingStatus;
use App\Enums\CourseImageType;
use App\Enums\Gender;
use App\Enums\ReservationStatus;
use App\Enums\StaffStatus;
use App\Enums\Status;
use App\Enums\Permission;
use App\Enums\WebReception;
use App\Enums\CalendarDisplay;
use App\Enums\PaymentStatus;
use App\Enums\TerminalType;

return [
    Authority::class => [
        Authority::ADMIN => 'System Administrator',
        Authority::MEMBER => 'Member',
        Authority::EXTERNAL_STAFF => 'External Staff',
        Authority::CONTRACT_STAFF => 'Contract Staff'
    ],

    StaffStatus::class => [
        StaffStatus::VALID => 'Valid',
        StaffStatus::INVALID => 'Invalid',
        StaffStatus::DELETED => 'Deleted'
    ],

    Permission::class => [
        Permission::NONE => 'None',
        Permission::VIEW => 'View',
        Permission::EDIT => 'Edit',
        Permission::UPLOAD => 'Upload'
    ],

    Status::class => [
        Status::VALID => 'Valid',
        Status::DELETED => 'Deleted'
    ],

    WebReception::class => [
        WebReception::NOT_ACCEPT => 'Not Accept',
        WebReception::ACCEPT => 'Accept'
    ],

    CalendarDisplay::class => [
        CalendarDisplay::HIDE => 'Hide',
        CalendarDisplay::SHOW => 'Show'
    ],

    ReservationStatus::class => [
        ReservationStatus::PENDING => 'Pending',
        ReservationStatus::RECEPTION_COMPLETED => 'Reception Completed',
        ReservationStatus::COMPLETED => 'Completed',
        ReservationStatus::CANCELLED => 'Cancelled'
    ],

    PaymentStatus::class => [
        PaymentStatus::TEMPORARY_SALE => 'Temporary Sale',
        PaymentStatus::ACTUAL_SALE => 'Actual Sale',
        PaymentStatus::CANCELLATION => 'Cancellation',
        PaymentStatus::ERROR => 'Error'
    ],

    Gender::class =>[
        Gender::MALE => 'Male',
        Gender::FEMALE => 'Female'
    ],

    TerminalType::class => [
        TerminalType::HOSPITAL => 'Hospital',
        TerminalType::PC => 'PC',
        TerminalType::SMART_PHONE => 'Smart Phone',
        TerminalType::PHONE_RESERVATION_API => 'Phone Reservation(API)',
        TerminalType::PHONE_RESERVATION_PPC => 'Phone Reservation(PPC)'
    ],

    CourseImageType::class => [
        CourseImageType::MAIN => 'MainCourse',
        CourseImageType::PC => 'Detail for pc',
        CourseImageType::SP => 'Detail for sp',
        CourseImageType::Main => 'MainCourse',
        CourseImageType::Pc => 'Detail for pc',
        CourseImageType::Sp => 'Detail for sp',
    ],

    BillingStatus::class => [
	    BillingStatus::Unconfirmed => 'Unconfirmed',
	    BillingStatus::Checking => 'Checking',
	    BillingStatus::Confirmed => 'Confirmed',
	    BillingStatus::Confirm => 'Confirm',
    ]

];
