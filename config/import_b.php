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
    'calendar_days.csv' => [
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
    'T_AP_M_COMMON_SETTING.csv' => [
        'model' => \App\HospitalEmailSetting::class,
        'import' => \App\Imports\HospitalEmailSettingImport::class,
    ],
    'T_OP_T_DOCK_PAYMENTS.csv' => [
        'model' => \App\Reservation::class,
        'import' => \App\Imports\ReservationPaymentImport::class,
    ],
    'T_OP_M_HYB_APPOINT_LINEGROUP.csv'=>[
        'model' => \App\Course::class,
        'import' => \App\Imports\CourseExtraImport::class,
    ],
    'T_OP_T_HYB_APPOINT.csv' => [
        'model' => \App\Reservation::class,
        'import' => \App\Imports\ReservationImport::class,
    ],
    'T_OP_T_HYB_APPOINT_DETAIL.csv' => [
        'model' => \App\Reservation::class,
        'import' => \App\Imports\ReservationDetailImport::class,
    ],
    'T_CR_M_EMAIL_HISTORY.csv' => [
        'model' => \App\MailHistory::class,
        'import' => \App\Imports\MailHistoryImport::class,
    ],
];
