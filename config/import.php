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
    'm_course_detail.csv' => [
        'model' => CourseDetail::class,
        'import' => CourseDetailImport::class,
        'seed' => false,
    ],
    'm_course_question.csv' => [
        'model' => CourseQuestion::class,
        'import' => CourseQuestionImport::class,
        'seed' => false,
    ],
    'm_file_location.csv' => [
        'model' => ImageOrder::class,
        'import' => ImageOrderImport::class,
        'seed' => false,
    ],
    'm_hospital_file.csv' => [
        'model' => HospitalImage::class,
        'import' => HospitalImageImport::class,
        'seed' => false,
    ],
    'm_hospital_time.csv' => [
        'model' => MedicalTreatmentTime::class,
        'import' => MedicalTreatmentTimeImport::class,
        'seed' => false,
    ],
    'm_hospital_detail.csv' => [
        'model' => HospitalDetail::class,
        'import' => HospitalDetailImport::class,
        'seed' => false,
    ],
    'm_course_file.csv' => [
        'model' => CourseImage::class,
        'import' => CourseImageImport::class,
        'seed' => false,
    ],
    'm_hospital_category.csv' => [
        'model' => HospitalCategory::class,
        'import' => HospitalCategoryImport::class,
        'seed' => false,
    ],
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
    'm_option.csv' => [
        'model' => Option::class,
        'import' => OptionImport::class,
        'seed' => true,
    ],
    'm_course_option_group.csv' => [
        'model' => CourseOption::class,
        'import' => CourseOptionImport::class,
        'seed' => false,
    ],
    't_pv.csv' => [
        'model' => PvRecord::class,
        'import' => PvRecordImport::class,
        'seed' => false,
    ],
    't_consideration_list.csv' => [
        'model' => ConsiderationList::class,
        'import' => ConsiderationListImport::class,
        'seed' => false,
    ],
    'm_user.csv' => [
        'model' => MemberLoginInfo::class,
        'import' => MemberLoginInfoImport::class,
        'seed' => false,
    ],
//    't_reserve_claim.csv' => [
//        'model' => \App\ReservationOption::class,
//        'import' => \App\Imports\ReservationOptionImport::class,
//        'seed' => true,
//    ],
];
