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
];
