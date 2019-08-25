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
];
