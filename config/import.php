<?php

use App\ClassificationType;
use App\Hospital;
use App\Imports\ClassificationTypeImport;
use App\Imports\HospitalImport;
use App\Imports\MajorClassificationImport;
use App\MajorClassification;

return [
    'm_item_type.csv' => [
        'model' => ClassificationType::class,
        'import' => ClassificationTypeImport::class,
    ],
    'm_item_category_dai.csv' => [
        'model' => MajorClassification::class,
        'import' => MajorClassificationImport::class,
    ],
    'm_hospital.csv' => [
        'model' => Hospital::class,
        'import' => HospitalImport::class,
    ]
];
