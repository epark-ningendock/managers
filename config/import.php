<?php

use App\ClassificationType;
use App\Hospital;
use App\HospitalMajorClassification;
use App\HospitalMiddleClassification;
use App\HospitalMinorClassification;
use App\Imports\ClassificationTypeImport;
use App\Imports\HospitalImport;
use App\Imports\HospitalMajorClassificationImport;
use App\Imports\HospitalMiddleClassificationImport;
use App\Imports\HospitalMinorClassificationImport;
use App\Imports\MajorClassificationImport;
use App\Imports\MiddleClassificationImport;
use App\Imports\MinorClassificationImport;
use App\MajorClassification;
use App\MiddleClassification;
use App\MinorClassification;

return [
    'm_item_type.csv' => [
        'model' => ClassificationType::class,
        'import' => ClassificationTypeImport::class,
    ],
    'm_item_category_dai.csv' => [
        'model' => MajorClassification::class,
        'import' => MajorClassificationImport::class,
    ],
    'm_item_category_chu.csv' => [
        'model' => MiddleClassification::class,
        'import' => MiddleClassificationImport::class,
    ],
    'm_item_category_sho.csv' => [
        'model' => MinorClassification::class,
        'import' => MinorClassificationImport::class,
    ],
    'm_hospital.csv' => [
        'model' => Hospital::class,
        'import' => HospitalImport::class,
    ],
    'm_hospital_category_dai.csv' => [
        'model' => HospitalMajorClassification::class,
        'import' => HospitalMajorClassificationImport::class,
    ],
    'm_hospital_category_chu.csv' => [
        'model' => HospitalMiddleClassification::class,
        'import' => HospitalMiddleClassificationImport::class,
    ],
    'm_hospital_category_sho.csv' => [
        'model' => HospitalMinorClassification::class,
        'import' => HospitalMinorClassificationImport::class,
    ],
];
