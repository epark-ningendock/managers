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
//    'm_pref.csv' => [
//        'model' => Prefecture::class,
//        'import' => PrefectureImport::class,
//        'seed' => true,
//    ],
//    'm_tax_class.csv' => [
//        'model' => TaxClass::class,
//        'import' => TaxImport::class,
//        'seed' => true,
//    ],
//    'm_district.csv' => [
//        'model' => DistrictCode::class,
//        'import' => DistrictCodeImport::class,
//        'seed' => true,
//    ],
//    'm_item_type.csv' => [
//        'model' => ClassificationType::class,
//        'import' => ClassificationTypeImport::class,
//        'seed' => true,
//    ],
//    'm_item_category_dai.csv' => [
//        'model' => MajorClassification::class,
//        'import' => MajorClassificationImport::class,
//        'seed' => true,
//    ],
//    'm_item_category_chu.csv' => [
//        'model' => MiddleClassification::class,
//        'import' => MiddleClassificationImport::class,
//        'seed' => true,
//    ],
//    'm_item_category_sho.csv' => [
//        'model' => MinorClassification::class,
//        'import' => MinorClassificationImport::class,
//        'seed' => true,
//    ],
//    'm_contract_plan.csv' => [
//        'model' => ContractPlan::class,
//        'import' => ContractPlanImport::class,
//        'seed' => false,
//    ],
//    'm_hospital.csv' => [
//        'model' => Hospital::class,
//        'import' => HospitalImport::class,
//        'seed' => true,
//    ],
//    'm_hospital_category_dai.csv' => [
//        'model' => HospitalMajorClassification::class,
//        'import' => HospitalMajorClassificationImport::class,
//        'seed' => true,
//    ],
//    'm_hospital_category_chu.csv' => [
//        'model' => HospitalMiddleClassification::class,
//        'import' => HospitalMiddleClassificationImport::class,
//        'seed' => true,
//    ],
//    'm_hospital_category_sho.csv' => [
//        'model' => HospitalMinorClassification::class,
//        'import' => HospitalMinorClassificationImport::class,
//        'seed' => true,
//    ],
//    'm_staff.csv' => [
//        'model' => Staff::class,
//        'import' => StaffImport::class,
//        'seed' => false,
//    ],
//    'm_course_basic.csv' => [
//        'model' => Course::class,
//        'import' => CourseImport::class,
//        'seed' => false,
//    ],

//    'm_rail_corp.csv' => [
//        'model' => RailwayCompany::class,
//        'import' => RailwayCompanyImport::class,
//        'seed' => true,
//    ],
//    'm_rail.csv' => [
//        'model' => Rail::class,
//        'import' => RailImport::class,
//        'seed' => true,
//    ],
//    'm_station.csv' => [
//        'model' => Station::class,
//        'import' => StationImport::class,
//        'seed' => true,
//    ],
//    'm_rail_station.csv' => [
//        'model' => null, // 中間テーブルのため null
//        'import' => RailStationImport::class,
//        'seed' => true,
//    ],
//    'm_rail_pref.csv' => [
//        'model' => null, // 中間テーブルのため null
//        'import' => PrefectureRailImport::class,
//        'seed' => true,
//    ],


    'm_course_option_group.csv' => [
        'model' => CourseOption::class,
        'import' => CourseOptionImport::class,
        'seed' => false,
    ],
    'm_availability.csv' => [
        'model' => \App\Availabil::class,
        'import' => \App\Imports\AvailabilityImport::class,
    ],
//    't_reserve_claim.csv' => [
//        'model' => \App\ReservationOption::class,
//        'import' => \App\Imports\ReservationOptionImport::class,
//        'seed' => true,
//    ],
];
