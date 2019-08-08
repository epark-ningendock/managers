<?php

use App\Hospital;
use App\Imports\HospitalImport;

return [
    'm_hospital.csv' => [
        'model' => Hospital::class,
        'import' => HospitalImport::class,
    ]
];
