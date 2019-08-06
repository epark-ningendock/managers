<?php

use App\Hospital;
use App\Imports\HospitalImport;
use App\Imports\PrefectureImport;
use App\Prefecture;

return [
    'm_pref.csv' => [
        'model' => Prefecture::class,
        'import' => PrefectureImport::class,
    ],
    'm_hospital.csv' => [
        'model' => Hospital::class,
        'import' => HospitalImport::class,
    ]
];
