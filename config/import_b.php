<?php

/**
 * Bシステム
 */

return [
    'T_BA_M_USER.csv' => [
        'model' => \App\HospitalStaff::class,
        'import' => \App\Imports\HospitalStaffImport::class,
    ],
    'T_AD_M_SHOPOWNER.csv' => [
        'model' => \App\ContractInformation::class,
        'import' => \App\Imports\ContractInformationImport::class,
    ],
    'T_OP_M_HYB_APPOINT_LINE.csv' => [
        'model' => \App\Calendar::class,
        'import' => \App\Imports\CalendarImport::class,
    ],
    'T_OP_M_HYB_APPOINT_TIMEZONE.csv' => [
        'model' => \App\CalendarDay::class,
        'import' => \App\Imports\CalendarDayImport::class,
    ],
    'T_OP_M_HYB_APPOINT_HOLIDAY.csv' => [
        'model' => \App\Holiday::class,
        'import' => \App\Imports\HolidayImport::class,
    ],
    'T_CR_M_SIMULTANEOUS_TRANSMIT_MAIL_TMPL.csv' => [
        'model' => \App\EmailTemplate::class,
        'import' => \App\Imports\EmailTemplateImport::class,
    ],
    'T_OP_M_HYB_APPOINT_CUSTOMER.csv' => [
        'model' => \App\Customer::class,
        'import' => \App\Imports\CustomerImport::class,
    ],
    'T_OP_T_HYB_APPOINT.csv' => [
        'model' => \App\Reservation::class,
        'import' => \App\Imports\ReservationImport::class,
    ],
    'T_OP_T_HYB_APPOINT_DETAIL.csv' => [
        'model' => \App\Reservation::class,
        'import' => \App\Imports\ReservationDetailImport::class,
    ],
    'T_OP_T_DOCK_PAYMENTS.csv' => [
        'model' => \App\Reservation::class,
        'import' => \App\Imports\ReservationPaymentImport::class,
    ],
];
