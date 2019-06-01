<?php
/**
 * Created by PhpStorm.
 * User: thanhtetaung
 * Date: 2019-04-02
 * Time: 23:19
 */

use \App\Enums\Authority;
use App\Enums\StaffStatus;
use App\Enums\Status;
use App\Enums\Permission;
use App\Enums\WebReception;
use App\Enums\CalendarDisplay;

return [
    Authority::class => [
        Authority::Admin => 'System Administrator',
        Authority::Member => 'Member',
        Authority::ExternalStaff => 'External Staff'
    ],

    StaffStatus::class => [
        StaffStatus::Valid => 'Valid',
        StaffStatus::Invalid => 'Invalid',
        StaffStatus::Deleted => 'Deleted'
    ],

    Permission::class => [
        Permission::None => 'None',
        Permission::View => 'View',
        Permission::Edit => 'Edit',
        Permission::Upload => 'Upload'
    ],

    Status::class => [
        Status::Valid => 'Valid',
        Status::Deleted => 'Deleted'
    ],

    WebReception::class => [
        WebReception::NotAccept => 'Not Accept',
        WebReception::Accept => 'Accept'
    ],

    CalendarDisplay::class => [
        CalendarDisplay::Hide => 'Hide',
        CalendarDisplay::Show => 'Show'
    ]

];
