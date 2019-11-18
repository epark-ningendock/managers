<?php

use App\ClassificationType;
use App\ConsiderationList;
use App\ContractPlan;
use App\Course;
use App\CourseDetail;
use App\CourseImage;
use App\CourseOption;
use App\CourseQuestion;
use App\DistrictCode;
use App\Hospital;
use App\HospitalCategory;
use App\HospitalDetail;
use App\HospitalImage;
use App\HospitalMajorClassification;
use App\HospitalMiddleClassification;
use App\HospitalMinorClassification;
use App\ImageOrder;
use App\Imports\ClassificationTypeImport;
use App\Imports\ConsiderationListImport;
use App\Imports\ContractPlanImport;
use App\Imports\CourseDetailImport;
use App\Imports\CourseImageImport;
use App\Imports\CourseImport;
use App\Imports\CourseOptionImport;
use App\Imports\CourseQuestionImport;
use App\Imports\DistrictCodeImport;
use App\Imports\HospitalCategoryImport;
use App\Imports\HospitalDetailImport;
use App\Imports\HospitalImageImport;
use App\Imports\HospitalImport;
use App\Imports\HospitalMajorClassificationImport;
use App\Imports\HospitalMiddleClassificationImport;
use App\Imports\HospitalMinorClassificationImport;
use App\Imports\ImageOrderImport;
use App\Imports\MajorClassificationImport;
use App\Imports\MedicalTreatmentTimeImport;
use App\Imports\MemberLoginInfoImport;
use App\Imports\MiddleClassificationImport;
use App\Imports\MinorClassificationImport;
use App\Imports\OptionImport;
use App\Imports\PrefectureImport;
use App\Imports\PrefectureRailImport;
use App\Imports\PvRecordImport;
use App\Imports\RailImport;
use App\Imports\RailStationImport;
use App\Imports\RailwayCompanyImport;
use App\Imports\StaffImport;
use App\Imports\StationImport;
use App\Imports\TaxImport;
use App\MajorClassification;
use App\MedicalTreatmentTime;
use App\MemberLoginInfo;
use App\MiddleClassification;
use App\MinorClassification;
use App\Option;
use App\Prefecture;
use App\PvRecord;
use App\Rail;
use App\RailwayCompany;
use App\Staff;
use App\Station;
use App\TaxClass;

return [
    // ステージングでのインポートの為、一部コメントアウトしています



    'm_course_basic.csv' => [
        'model' => Course::class,
        'import' => CourseImport::class,
        'seed' => false,
    ],

//    't_reserve_claim.csv' => [
//        'model' => \App\ReservationOption::class,
//        'import' => \App\Imports\ReservationOptionImport::class,
//        'seed' => true,
//    ],
];
