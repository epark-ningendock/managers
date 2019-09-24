<?php

use App\ClassificationType;
use App\DistrictCode;
use App\Hospital;
use App\HospitalMajorClassification;
use App\HospitalMiddleClassification;
use App\HospitalMinorClassification;
use App\Imports\ClassificationTypeImport;
use App\Imports\DistrictCodeImport;
use App\Imports\HospitalImport;
use App\Imports\HospitalMajorClassificationImport;
use App\Imports\HospitalMiddleClassificationImport;
use App\Imports\HospitalMinorClassificationImport;
use App\Imports\MajorClassificationImport;
use App\Imports\MiddleClassificationImport;
use App\Imports\MinorClassificationImport;
use App\Imports\PrefectureImport;
use App\Imports\PrefectureRailImport;
use App\Imports\RailImport;
use App\Imports\RailStationImport;
use App\Imports\RailwayCompanyImport;
use App\Imports\StationImport;
use App\MajorClassification;
use App\MiddleClassification;
use App\MinorClassification;
use App\Prefecture;
use App\Rail;
use App\RailwayCompany;
use App\Station;

return [
    // ステージングでのインポートの為、一部コメントアウトしています
//    'm_pref.csv' => [
//        'model' => Prefecture::class,
//        'import' => PrefectureImport::class,
//    ],
//    'm_district.csv' => [
//        'model' => DistrictCode::class,
//        'import' => DistrictCodeImport::class
//    ],
//    'm_item_type.csv' => [
//        'model' => ClassificationType::class,
//        'import' => ClassificationTypeImport::class,
//    ],
//    'm_item_category_dai.csv' => [
//        'model' => MajorClassification::class,
//        'import' => MajorClassificationImport::class,
//    ],
//    'm_item_category_chu.csv' => [
//        'model' => MiddleClassification::class,
//        'import' => MiddleClassificationImport::class,
//    ],
//    'm_item_category_sho.csv' => [
//        'model' => MinorClassification::class,
//        'import' => MinorClassificationImport::class,
//    ],
//    'm_hospital.csv' => [
//        'model' => Hospital::class,
//        'import' => HospitalImport::class,
//    ],
//    'm_hospital_category_dai.csv' => [
//        'model' => HospitalMajorClassification::class,
//        'import' => HospitalMajorClassificationImport::class,
//    ],
//    'm_hospital_category_chu.csv' => [
//        'model' => HospitalMiddleClassification::class,
//        'import' => HospitalMiddleClassificationImport::class,
//    ],
//    'm_hospital_category_sho.csv' => [
//        'model' => HospitalMinorClassification::class,
//        'import' => HospitalMinorClassificationImport::class,
//    ],
    // 'm_staff.csv' => [
    //     'model' => Staff::class,
    //     'import' => StaffImport::class,
    // ],
    // 'm_course_basic.csv' => [
    //     'model' => Course::class,
    //     'import' => CourseImport::class,
    // ],
    // 'm_course_detail.csv' => [
    //     'model' => CourseDetail::class,
    //     'import' => CourseDetailImport::class,
    // ],
    // 'm_course_question.csv' => [
    //     'model' => CourseQuestion::class,
    //     'import' => CourseQuestionImport::class,
    // ],
    // 'm_file_location.csv' => [
    //     'model' => ImageOrder::class,
    //     'import' => ImageOrderImport::class,
    // ],
    // 'm_hospital_file.csv' => [
    //     'model' => HospitalImage::class,
    //     'import' => HospitalImageImport::class,
    // ],
    // 'm_hospital_time.csv' => [
    //     'model' => MedicalTreatmentTime::class,
    //     'import' => MedicalTreatmentTimeImport::class,
    // ],
    // 'm_hospital_detail.csv' => [
    //     'model' => HospitalDetail::class,
    //     'import' => HospitalDetailImport::class,
    // ],
    // 'm_course_file.csv' => [
    //     'model' => CourseImage::class,
    //     'import' => CourseImageImport::class,
    // ],
    // 'm_contract_plan.csv' => [
    //     'model' => ContractPlan::class,
    //     'import' => ContractPlanImport::class,
    // ],
    // 'm_hospital_category.csv' => [
    //     'model' => HospitalCategory::class,
    //     'import' => HospitalCategoryImport::class,
    // ],
//    'm_rail_corp.csv' => [
//        'model' => RailwayCompany::class,
//        'import' => RailwayCompanyImport::class,
//    ],
//    'm_rail.csv' => [
//        'model' => Rail::class,
//        'import' => RailImport::class,
//    ],
//    'm_station.csv' => [
//        'model' => Station::class,
//        'import' => StationImport::class,
//    ],
//    'm_rail_station.csv' => [
//        'model' => null, // 中間テーブルのため null
//        'import' => RailStationImport::class,
//    ],
//    'm_rail_pref.csv' => [
//        'model' => null, // 中間テーブルのため null
//        'import' => PrefectureRailImport::class,
//    ],
    // 'm_option.csv' => [
    //     'model' => Option::class,
    //     'import' => OptionImport::class,
    // ],
    // 't_pv.csv' => [
    //     'model' => PvRecord::class,
    //     'import' => PvRecordImport::class,
    // ],
    'm_user.csv' => [
        'model' => \App\MemberLoginInfo::class,
        'import' => \App\Imports\MemberLoginInfoImport::class,
    ],
];
