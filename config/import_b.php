<?php

/**
 * Bシステム
 */
return [
    'T_AD_M_SHOPOWNER' => [
        'model' => \App\ContractInformation::class,
        'import' => \App\Imports\ContractInformationImport::class,
    ],
    'T_BA_M_USER' => [
        'model' => \App\HospitalStaff::class,
        'import' => \App\Imports\HospitalStaffImport::class,
    ],
];
