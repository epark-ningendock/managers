<?php

use App\ClassificationType;
use App\Course;
use App\CourseDetail;
use App\CourseQuestion;
use App\Hospital;
use App\HospitalDetail;
use App\HospitalImage;
use App\HospitalMajorClassification;
use App\HospitalMiddleClassification;
use App\HospitalMinorClassification;
use App\Imports\ClassificationTypeImport;
use App\Imports\CourseDetailImport;
use App\Imports\CourseImport;
use App\Imports\CourseQuestionImport;
use App\Imports\HospitalDetailImport;
use App\Imports\HospitalImageImport;
use App\Imports\HospitalImport;
use App\Imports\HospitalMajorClassificationImport;
use App\Imports\HospitalMiddleClassificationImport;
use App\Imports\HospitalMinorClassificationImport;
use App\Imports\MajorClassificationImport;
use App\Imports\MedicalTreatmentTimeImport;
use App\Imports\MiddleClassificationImport;
use App\Imports\MinorClassificationImport;
use App\Imports\PrefectureImport;
use App\Imports\StaffImport;
use App\MajorClassification;
use App\MedicalTreatmentTime;
use App\MiddleClassification;
use App\MinorClassification;
use App\Prefecture;
use App\Staff;

return [
    'm_pref.csv' => [
        'model' => Prefecture::class,
        'import' => PrefectureImport::class,
    ],
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
    'm_staff.csv' => [
        'model' => Staff::class,
        'import' => StaffImport::class,
    ],
    'm_course_basic.csv' => [
        'model' => Course::class,
        'import' => CourseImport::class,
    ],
    'm_course_detail.csv' => [
        'model' => CourseDetail::class,
        'import' => CourseDetailImport::class,
    ],
    'm_course_question.csv' => [
        'model' => CourseQuestion::class,
        'import' => CourseQuestionImport::class,
    ],
    'm_hospital_file.csv' => [
        'model' => HospitalImage::class,
        'import' => HospitalImageImport::class,
    ],
    'm_hospital_time.csv' => [
        'model' => MedicalTreatmentTime::class,
        'import' => MedicalTreatmentTimeImport::class,
    ],
    'm_hospital_detail.csv' => [
        'model' => HospitalDetail::class,
        'import' => HospitalDetailImport::class,
    ],
];
