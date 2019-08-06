<?php

use App\ClassificationType;
use App\Hospital;
use App\Imports\ClassificationTypeImport;
use App\Imports\HospitalImport;

return [
    'm_item_type.csv' => [
        'model' => ClassificationType::class,
        'import' => ClassificationTypeImport::class,
    ],
    'm_hospital.csv' => [
        'model' => Hospital::class,
        'import' => HospitalImport::class,
    ]
];
