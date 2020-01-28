<?php

namespace App\Http\Controllers\Api;

use App\Enums\ReservationStatus;
use App\Enums\Status;
use App\Enums\WebReception;
use App\Holiday;
use App\HospitalMetaInformation;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\HospitalSearchRequest;

use App\Hospital;
use App\Course;

use App\Http\Resources\SearchHospitalsResource;
use App\Http\Resources\SearchCoursesResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Log;

class SearchController extends ApiBaseController
{
    /**
     * 医療機関・検査コース一覧検索API
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(SearchRequest $request)
    {
//        try {
            $search_cond_chk_result = $this->checkSearchCond($request, false);
            if (!$search_cond_chk_result[0]) {
                return $this->createResponse($search_cond_chk_result[1]);
            }
            // フラグセット
            $return_flag = $request->input('return_flag');
            $search_condition_return_flag = $request->input('search_condition_return_flag');

            // 対象データ取得
            $hospitals = $this->getHospitals($request);
            $courses = $this->getCourses($request);

            // 結果生成
            $status = 0;

            // 件数要素セット
            $count = $hospitals->count() + $courses->count();

            //医療施設検索ヒット数セット
            $hospitals_return_count = $hospitals->count();

            //検査コース検索ヒット数セット
            $courses_return_count = $courses->count();

            // page取得の場合、全件件数取得
            $hospitals_search_count = $return_flag == 0 ? $hospitals->count() : $this->getHospitals($request, true);
            $courses_search_count = $return_flag == 0 ? $courses->count() : $this->getCourses($request, true);

            $return_count = $count;
            $return_from = $return_flag == 0 ? 1 : $request->input('return_from');
            $return_to = $return_flag == 0 ? $count : $request->input('return_to');

            // 対象データ取得
            $hospitals = SearchHospitalsResource::collection($hospitals);
            $courses = SearchCoursesResource::collection($courses);

            // response
            return $search_condition_return_flag == 0 ?
                compact('status', 'hospitals_return_count', 'courses_return_count', 'hospitals_search_count', 'courses_search_count', 'return_from', 'return_to', 'hospitals', 'courses')
                : compact('status', 'hospitals_return_count', 'courses_return_count', 'hospitals_search_count', 'courses_search_count', 'return_from', 'return_to')
                + $request->toJson()
                + compact('hospitals', 'courses');

//        } catch (\Exception $e) {
//            Log::error($e);
//            return $this->createResponse($this->messages['system_error_api']);
//        }

    }

    /**
     * 医療機関一覧検索API
     *
     * @param  App\Http\Requests\HospitalSearchRequest $request
     * @return \Illuminate\Http\Response
     */
    public function hospitals(HospitalSearchRequest $request)
    {
//        try {
            $search_cond_chk_result = $this->checkSearchCond($request, true);
            if (!$search_cond_chk_result[0]) {
                return $this->createResponse($search_cond_chk_result[1]);
            }
            // フラグセット
            $return_flag = $request->input('return_flag');
            $search_count_only_flag = $request->input('search_count_only_flag');
            $search_condition_return_flag = $request->input('search_condition_return_flag');

            // 件数のみ返却
            if ($search_count_only_flag == 1) {
                $search_count = $this->getHospitalCount($request, true);
            } else {
                $search_count = $this->getHospitalCount($request, true);
                $targets =  $this->getHospitalCount($request, false);
                $entities = $this->getHospitals($targets);

            }

            // 対象データ取得
//            $entities = $this->getHospitals($request);

            // 結果生成
            $status = 0;

            // 件数要素セット
            // page取得の場合、全件件数取得
//            $search_count = $return_flag == 0 ? $entities->count() : $this->getHospitals($request, true);
            $return_count = count($entities);
//        $return_count = 0;
            $return_from = $return_flag == 0 ? 1 : $request->input('return_from');
            $return_to = $return_flag == 0 ? $search_count : $request->input('return_to');

            // 件数のみ返却
            if ($search_count_only_flag == 1) {
                return $search_condition_return_flag == 0 ?
                    compact('status', 'search_count', 'return_count', 'return_from', 'return_to')
                    :
                    compact('status', 'search_count', 'return_count', 'return_from', 'return_to') + $request->toJson();
            }

            // レスポンス生成
            $hospitals = SearchHospitalsResource::collection($entities);

            // response
            return $search_condition_return_flag == 0 ?
                compact('status', 'search_count', 'return_count', 'return_from', 'return_to', 'hospitals')
                : compact('status', 'search_count', 'return_count', 'return_from', 'return_to')
                + $request->toJson()
                + compact('hospitals');
//        } catch (\Exception $e) {
//            Log::error($e);
//            return $this->createResponse($this->messages['system_error_api']);
//        }

    }

    /**
     * 検査コース一覧検索API
     *
     * @param  App\Http\Requests\SearchRequest $request
     * @return \Illuminate\Http\Response
     */
    public function courses(SearchRequest $request)
    {
//        try {
            $search_cond_chk_result = $this->checkSearchCond($request, false);
            if (!$search_cond_chk_result[0]) {
                return $this->createResponse($search_cond_chk_result[1]);
            }
            // フラグセット
            $return_flag = $request->input('return_flag');
            $search_condition_return_flag = $request->input('search_condition_return_flag');

        // 件数のみ返却
        $search_count = $this->getCourseCount($request, true);
        $targets =  $this->getCourseCount($request, false);
        $entities = $this->getCourses($targets);

            // 結果生成
            $status = 0;

            // 件数要素セット
            // page取得の場合、全件件数取得
//            $search_count = $return_flag == 0 ? $entities->count() : $this->getCourses($request, true);
            $return_count = $entities->count();
            $return_from = $return_flag == 0 ? 1 : $request->input('return_from');
            $return_to = $return_flag == 0 ? $search_count : $request->input('return_to');

            // レスポンス生成
            $courses = SearchCoursesResource::collection($entities);

            // response
            return $search_condition_return_flag == 0 ?
                compact('status', 'search_count', 'return_count', 'return_from', 'return_to', 'courses')
                : compact('status', 'search_count', 'return_count', 'return_from', 'return_to')
                + $request->toJson()
                + compact('courses');
//        } catch (\Exception $e) {
//            Log::error($e);
//            return $this->createResponse($this->messages['system_error_api']);
//        }

    }

    /**
     * @param $request
     */
    private function getHospitalCount($request, $count_flg) {
        $reservation_dt = $request->input('reservation_dt');
        $target = null;
        if (isset($reservation_dt)) {
            $target = Carbon::createMidnightDate(
                substr($reservation_dt, 0, 4),
                substr($reservation_dt, 4, 2),
                substr($reservation_dt, 6, 2))->toDateString();
        } else {
            $target = Carbon::today()->toDateString();
        }
        $query = Hospital::select('hospitals.id', 'hospitals.pvad', 'hospitals.pv_count')->distinct();
        $query->join('contract_informations', function ($join) {
            $join->on('hospitals.id', 'contract_informations.hospital_id')
                ->whereNotNull('contract_informations.code');
        });
        $query->join('hospital_metas', 'hospitals.id', 'hospital_metas.hospital_id');
        $query->leftJoin('courses', function ($join) use ($target) {
            $join->on('hospitals.id', 'courses.hospital_id')
                ->where('courses.web_reception', WebReception::ACCEPT)
                ->where('publish_start_date', '<=', $target)
                ->where('publish_end_date', '>=', $target);
        });

        $query->leftJoin('course_metas', 'courses.id', 'course_metas.course_id');
        if (isset($reservation_dt)) {
            $query->leftJoin('calendars', 'calendars.id', 'courses.calendar_id');
            $query->leftJoin('calendar_days', function ($join) use ($target) {
                $join->on('calendars.id', 'calendar_days.calendar_id')
                    ->where('reservation_frames', '>', 'reservation_count')
                    ->where('is_reservation_acceptance', 1)
                    ->whereDate('calendar_days.date', $target)
                    ->where('is_holiday', 0);
            });
        }

        if (isset($reservation_dt)) {
            $query->whereRaw('? >= DATE_ADD(CURRENT_DATE(), INTERVAL (30 * (reception_start_date DIV 1000) + MOD(reception_start_date, 1000)) DAY) ', [$target]);
            $query->whereDate('calendar_days.date', $target);
        }

        if (!empty($request->input('freewords'))) {
            $freewords = $request->input('freewords');
            $query->where('hospital_name', 'like', '%'.$freewords.'%');

        }

        // 公開医療機関指定
        $query->where('hospitals.status', Status::VALID);

        // 都道府県コード
        $pref_cd = $request->input('pref_cd');
        if (isset($pref_cd)) {
            $query->where('prefecture_id', $pref_cd);
        };

        // 市区町村コード
        $district_no = $request->input('district_no');
        if (isset($district_no)) {
            $districts = explode(',', $district_no);
            $query->where('district_code', $districts);
        };

        // 路線コード
        $rail_no = $request->input('rail_no');
        if (isset($rail_no)) {
            $rails = explode(',', $rail_no);
            $query->where(function ($q) use ($rails) {
                $q->whereIn('rail1', $rails)
                    ->orWhereIn('rail2', $rails)
                    ->orWhereIn('rail3', $rails)
                    ->orWhereIn('rail4', $rails)
                    ->orWhereIn('rail5', $rails);
            });
        };

        // 駅コード
        $station_no = $request->input('station_no');
        if (isset($station_no)) {
            $stations = explode(',', $station_no);
            $query->where(function ($q) use ($stations) {
                $q->whereIn('station1', $stations)
                    ->orWhereIn('station2', $stations)
                    ->orWhereIn('station3', $stations)
                    ->orWhereIn('station4', $stations)
                    ->orWhereIn('station5', $stations);
            });
        };

        // 条件設定フラグ
        $condition_add_flg = false;
        // 食事あり
        $meal_flg = false;
        // ペア
        $pear_flg = false;
        // 女性医師
        $female_doctor_flg = false;
        $course_category = $request->input('course_category_code');

        if (isset($course_category)) {
            foreach ($course_category as $code) {
                if ($code == '256') {
                    $meal_flg = true;
                }
                if ($code == '132') {
                    $pear_flg = true;
                }
                if ($code == '126') {
                    $female_doctor_flg = true;
                }
            }
        }

        // 検査種別
        $exam_types = $request->input('exam_type');
        if (isset($exam_types)) {
            $condition_add_flg = true;
            $exam_types = explode(',', $exam_types);
            $query->where(function ($q) use ($exam_types, $meal_flg, $pear_flg, $female_doctor_flg) {
                $q->where('course_metas.category_exam', 'like', '%' . $exam_types[0] . '%' );
                for($i = 1; $i < count($exam_types); $i++) {
                    $q->orWhere('course_metas.category_exam', 'like', '%' . $exam_types[$i] . '%' );
                }
                if ($meal_flg) {
                    $q->where('course_metas.meal_flg', 1);
                }
                if ($pear_flg) {
                    $q->where('course_metas.pear_flg', 1);
                }
                if ($female_doctor_flg) {
                    $q->where('course_metas.female_doctor_flg', 1);
                }
            });
        }

        // 対象となる疾患
        $diseases = $request->input('disease');
        if (isset($diseases)) {
            $condition_add_flg = true;
            $diseases = explode(',', $diseases);
            $query->where(function ($q) use ($diseases, $meal_flg, $pear_flg, $female_doctor_flg) {
                $q->where('course_metas.category_disease', 'like', '%' . $diseases[0] . '%' );
                for($i = 1; $i < count($diseases); $i++) {
                    $q->orWhere('course_metas.category_disease', 'like', '%' . $diseases[$i] . '%' );
                }
                if ($meal_flg) {
                    $q->where('course_metas.meal_flg', 1);
                }
                if ($pear_flg) {
                    $q->where('course_metas.pear_flg', 1);
                }
                if ($female_doctor_flg) {
                    $q->where('course_metas.female_doctor_flg', 1);
                }
            });
        }

        // 気になる部位
        $parts = $request->input('part');
        if (isset($parts)) {
            $condition_add_flg = true;
            $parts = explode(',', $parts);
            $query->where(function ($q) use ($parts, $meal_flg, $pear_flg, $female_doctor_flg) {
                $q->where('course_metas.category_part', 'like', '%' . $parts[0] . '%' );
                for($i = 1; $i < count($parts); $i++) {
                    $q->orWhere('course_metas.category_part', 'like', '%' . $parts[$i] . '%' );
                }
                if ($meal_flg) {
                    $q->where('course_metas.meal_flg', 1);
                }
                if ($pear_flg) {
                    $q->where('course_metas.pear_flg', 1);
                }
                if ($female_doctor_flg) {
                    $q->where('course_metas.female_doctor_flg', 1);
                }
            });
        }

        if (!$condition_add_flg) {
            if ($meal_flg) {
                $query->where('course_metas.meal_flg', 1);
            }
            if ($pear_flg) {
                $query->where('course_metas.pear_flg', 1);
            }
            if ($female_doctor_flg) {
                $query->where('course_metas.female_doctor_flg', 1);
            }
        }

        // コース金額(上限)
        $price_upper_limit = $request->input('price_upper_limit');
        if (isset($price_upper_limit)) {
            $query->where('courses.price', '<=', $price_upper_limit);
        }

        // コース金額(下限)
        $price_lower_limit = $request->input('price_lower_limit');
        if (isset($price_lower_limit)) {
            $query->where('courses.price', '>=', $price_lower_limit);
        }

        // 医療機関カテゴリ
        $hospital_category_code = $request->input('hospital_category_code');
        if (!empty($request->input('hospital_category_code'))) {
            $hospital_categories = explode(',', $hospital_category_code);
            foreach ($hospital_categories as $code) {
                if ($code == '5') {
                    $query->where('hospital_metas.credit_card_flg', 1);
                }
                if ($code == '1') {
                    $query->where('hospital_metas.parking_flg', 1);
                }
                if ($code == '3') {
                    $query->where('hospital_metas.pick_up_flg', 1);
                }
                if ($code == '16') {
                    $query->where('hospital_metas.children_flg', 1);
                }
                if ($code == '19') {
                    $query->where('hospital_metas.dedicate_floor_flg', 1);
                }
            }
        }

        // limit/offset
        if (!$count_flg) {
            $offset = intval($request->input('return_from')-1);
            $limit = intval($request->input('return_to')) - $offset;
            $query->offset($offset)->limit($limit);
        }

        // 並び順
        $query->orderBy('hospitals.pvad', 'asc');
        $query->orderBy('hospitals.pv_count', 'asc');

        $results = $query->get();

        if ($count_flg) {
            return count($results);
        }

        return $results;
    }

    /**
     * @param $request
     * @param $count_flg
     * @return int
     */
    private function getCourseCount($request, $count_flg) {
        $reservation_dt = $request->input('reservation_dt');
        $target = null;
        if (isset($reservation_dt)) {
            $target = Carbon::createMidnightDate(
                substr($reservation_dt, 0, 4),
                substr($reservation_dt, 4, 2),
                substr($reservation_dt, 6, 2))->toDateString();
        } else {
            $target = Carbon::today()->toDateString();
        }

        $query = Course::select('courses.id', 'courses.order', 'hospitals.pvad', 'hospitals.pv_count')->distinct();
        $query->join('hospitals', function ($join) {
            $join->on('hospitals.id', 'courses.hospital_id')
                ->where('hospitals.status', Status::VALID);
        });
        $query->join('contract_informations', function ($join) {
            $join->on('hospitals.id', 'contract_informations.hospital_id')
                ->whereNotNull('contract_informations.code');
        });
        $query->join('hospital_metas', 'hospitals.id', 'hospital_metas.hospital_id');

        $query->leftJoin('course_metas', 'courses.id', 'course_metas.course_id');
        if (isset($reservation_dt)) {
            $query->leftJoin('calendars', 'calendars.id', 'courses.calendar_id');
            $query->leftJoin('calendar_days', function ($join) use ($target) {
                $join->on('calendars.id', 'calendar_days.calendar_id')
                    ->where('reservation_frames', '>', 'reservation_count')
                    ->where('is_reservation_acceptance', 1)
                    ->whereDate('calendar_days.date', $target)
                    ->where('is_holiday', 0);
            });
        }
        $query->where('courses.web_reception', WebReception::ACCEPT);
        $query->where('publish_start_date', '<=', $target);
        $query ->where('publish_end_date', '>=', $target);

        if (isset($reservation_dt)) {
            $query->whereRaw('? >= DATE_ADD(CURRENT_DATE(), INTERVAL (30 * (reception_start_date DIV 1000) + MOD(reception_start_date, 1000)) DAY) ', [$target]);
            $query->whereDate('calendar_days.date', $target);
        }

        if (!empty($request->input('freewords'))) {
            $freewords = $request->input('freewords');
            $query->where('hospital_name', 'like', '%'.$freewords.'%');

        }

        // 都道府県コード
        $pref_cd = $request->input('pref_cd');
        if (isset($pref_cd)) {
            $query->where('prefecture_id', $pref_cd);
        };

        // 市区町村コード
        $district_no = $request->input('district_no');
        if (isset($district_no)) {
            $districts = explode(',', $district_no);
            $query->where('district_code', $districts);
        };

        // 路線コード
        $rail_no = $request->input('rail_no');
        if (isset($rail_no)) {
            $rails = explode(',', $rail_no);
            $query->where(function ($q) use ($rails) {
                $q->whereIn('rail1', $rails)
                    ->orWhereIn('rail2', $rails)
                    ->orWhereIn('rail3', $rails)
                    ->orWhereIn('rail4', $rails)
                    ->orWhereIn('rail5', $rails);
            });
        };

        // 駅コード
        $station_no = $request->input('station_no');
        if (isset($station_no)) {
            $stations = explode(',', $station_no);
            $query->where(function ($q) use ($stations) {
                $q->whereIn('station1', $stations)
                    ->orWhereIn('station2', $stations)
                    ->orWhereIn('station3', $stations)
                    ->orWhereIn('station4', $stations)
                    ->orWhereIn('station5', $stations);
            });
        };

        // 条件設定フラグ
        $condition_add_flg = false;
        // 食事あり
        $meal_flg = false;
        // ペア
        $pear_flg = false;
        // 女性医師
        $female_doctor_flg = false;
        $course_category = $request->input('course_category_code');

        if (isset($course_category)) {
            foreach ($course_category as $code) {
                if ($code == '256') {
                    $meal_flg = true;
                }
                if ($code == '132') {
                    $pear_flg = true;
                }
                if ($code == '126') {
                    $female_doctor_flg = true;
                }
            }
        }

        // 検査種別
        $exam_types = $request->input('exam_type');
        if (isset($exam_types)) {
            $condition_add_flg = true;
            $exam_types = explode(',', $exam_types);
            $query->where(function ($q) use ($exam_types, $meal_flg, $pear_flg, $female_doctor_flg) {
                $q->where('course_metas.category_exam', 'like', '%' . $exam_types[0] . '%' );
                for($i = 1; $i < count($exam_types); $i++) {
                    $q->orWhere('course_metas.category_exam', 'like', '%' . $exam_types[$i] . '%' );
                }
                if ($meal_flg) {
                    $q->where('course_metas.meal_flg', 1);
                }
                if ($pear_flg) {
                    $q->where('course_metas.pear_flg', 1);
                }
                if ($female_doctor_flg) {
                    $q->where('course_metas.female_doctor_flg', 1);
                }
            });
        }

        // 対象となる疾患
        $diseases = $request->input('disease');
        if (isset($diseases)) {
            $condition_add_flg = true;
            $diseases = explode(',', $diseases);
            $query->where(function ($q) use ($diseases, $meal_flg, $pear_flg, $female_doctor_flg) {
                $q->where('course_metas.category_disease', 'like', '%' . $diseases[0] . '%' );
                for($i = 1; $i < count($diseases); $i++) {
                    $q->orWhere('course_metas.category_disease', 'like', '%' . $diseases[$i] . '%' );
                }
                if ($meal_flg) {
                    $q->where('course_metas.meal_flg', 1);
                }
                if ($pear_flg) {
                    $q->where('course_metas.pear_flg', 1);
                }
                if ($female_doctor_flg) {
                    $q->where('course_metas.female_doctor_flg', 1);
                }
            });
        }

        // 気になる部位
        $parts = $request->input('part');
        if (isset($parts)) {
            $condition_add_flg = true;
            $parts = explode(',', $parts);
            $query->where(function ($q) use ($parts, $meal_flg, $pear_flg, $female_doctor_flg) {
                $q->where('course_metas.category_part', 'like', '%' . $parts[0] . '%' );
                for($i = 1; $i < count($parts); $i++) {
                    $q->orWhere('course_metas.category_part', 'like', '%' . $parts[$i] . '%' );
                }
                if ($meal_flg) {
                    $q->where('course_metas.meal_flg', 1);
                }
                if ($pear_flg) {
                    $q->where('course_metas.pear_flg', 1);
                }
                if ($female_doctor_flg) {
                    $q->where('course_metas.female_doctor_flg', 1);
                }
            });
        }

        if (!$condition_add_flg) {
            if ($meal_flg) {
                $query->where('course_metas.meal_flg', 1);
            }
            if ($pear_flg) {
                $query->where('course_metas.pear_flg', 1);
            }
            if ($female_doctor_flg) {
                $query->where('course_metas.female_doctor_flg', 1);
            }
        }

        // コース金額(上限)
        $price_upper_limit = $request->input('price_upper_limit');
        if (isset($price_upper_limit)) {
            $query->where('courses.price', '<=', $price_upper_limit);
        }

        // コース金額(下限)
        $price_lower_limit = $request->input('price_lower_limit');
        if (isset($price_lower_limit)) {
            $query->where('courses.price', '>=', $price_lower_limit);
        }

        // 医療機関カテゴリ
        $hospital_category_code = $request->input('hospital_category_code');
        if (!empty($request->input('hospital_category_code'))) {
            $hospital_categories = explode(',', $hospital_category_code);
            foreach ($hospital_categories as $code) {
                if ($code == '5') {
                    $query->where('hospital_metas.credit_card_flg', 1);
                }
                if ($code == '1') {
                    $query->where('hospital_metas.parking_flg', 1);
                }
                if ($code == '3') {
                    $query->where('hospital_metas.pick_up_flg', 1);
                }
                if ($code == '16') {
                    $query->where('hospital_metas.children_flg', 1);
                }
                if ($code == '19') {
                    $query->where('hospital_metas.dedicate_floor_flg', 1);
                }
            }
        }

        // limit/offset
        if (!$count_flg) {
            $offset = intval($request->input('return_from')-1);
            $limit = intval($request->input('return_to')) - $offset;
            $query->offset($offset)->limit($limit);
        }

        // 並び順
        $query->orderBy('hospitals.pvad', 'asc');
        $query->orderBy('hospitals.pv_count', 'asc');
        $query->orderBy('courses.order', 'asc');

        $results = $query->get();

        if ($count_flg) {
            return count($results);
        }

        return $results;
    }

    /**
     * @param $hospitals
     */
    private function getHospitals($hospitals) {

        $ids = [];
        foreach ($hospitals as $hospital) {
            $ids[] = $hospital->id;
        }

        $target_date = Carbon::today()->toDateString();

        return Hospital::with(['hospital_categories' => function ($query) {
            $query->whereIn('image_order', [2, 3, 4]);
        },
            'courses' => function ($query) use ($target_date) {
                $query->where('web_reception', WebReception::ACCEPT)
                ->where('publish_start_date', '<=', $target_date)
                ->where('publish_end_date', '>=', $target_date)
                    ->orderBy('order');
            },

        ])
        ->whereIn('id', $ids)
            ->orderBy('hospitals.pvad')
            ->orderBy('hospitals.pv_count')
            ->get();
    }

    /**
     * @param $courses
     * @return Course[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    private function getCourses($courses) {

        $ids = [];
        foreach ($courses as $course) {
            $ids[] = $course->id;
        }

        return Course::with([
            'hospital',
            'course_details' => function ($query) {
                $query->whereIn('major_classification_id', [1, 2, 3, 4, 5, 6, 11, 13, 24]);
            },
            'course_options'
        ])
            ->orderBy('order')
            ->get();
    }

//    /**
//     * 検査コースデータ取得
//     *
//     * @param  App\Http\Requests\SearchRequest  $request
//     * @param  bool                             $isCount 件数のみ:true
//     * @return Illuminate\Support\Collection    検索結果
//     */
//    private function getCourses($request, $isCount = false)
//    {
//        $from_date = Carbon::today();
//        $from = $from_date->year . sprintf('%02d', $from_date->month);
//
//        $to_date = Carbon::today()->addMonthsNoOverflow(2)->endOfMonth();
//        $to = $to_date->year . sprintf('%02d', $to_date->month);
//        $query = Course::query()
//            ->with([
//                'course_meta_informations',
//                'course_details',
//                'course_details.major_classification',
//                'course_details.middle_classification',
//                'course_details.minor_classification',
//                'course_options',
//                'course_images',
//                'hospital',
//                'contract_information',
//                'hospital.hospital_categories',
//            ])
//            ->where('is_category', 0)
//            ->whereHas('contract_information' , function($q) {
//                $q->whereNotNull('contract_informations.code');
//            })
//            ->whereHas('hospital' , function($q) {
//                $q->where('hospitals.status', Status::VALID);
//            })
//            ->where('courses.status', Status::VALID)
//            ->whereForSearchAPI($request);
//
//        // 件数のみ
//        if ($isCount === true) return $query->count();
//
//        // limit/offset
//        if ($request->input('return_flag') == 1) {
//            $offset = intval($request->input('return_from')-1);
//            $limit = intval($request->input('return_to')) - $offset;
//            $query->offset($offset)->limit($limit);
//        }
//        // string_limit_sizeの追加
//        $entities = $query->get();
//        foreach ($entities as $e) {
//            $e['string_limit_size'] = intval($request->input('string_limit_size'));
//        }
//        return $entities;
//    }
}
