<?php

namespace App\Http\Controllers\Api;

use App\Enums\GenderTak;
use App\Enums\HonninKbn;
use App\Enums\ReservationStatus;
use App\Enums\Status;
use App\Enums\WebReception;
use App\Holiday;
use App\HospitalMetaInformation;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\HospitalSearchRequest;
use App\Http\Requests\CourseSearchRequest;

use App\Hospital;
use App\Course;

use App\Http\Resources\SearchHospitalsResource;
use App\Http\Resources\SearchCoursesResource;
use App\TargetAge;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Log;

class SearchController extends ApiBaseController
{
    /**
     * 医療機関一覧検索API
     *
     * @param  App\Http\Requests\HospitalSearchRequest $request
     * @return \Illuminate\Http\Response
     */
    public function hospitals(HospitalSearchRequest $request)
    {
       try {
            $search_cond_chk_result = $this->checkSearchCond($request, true);
            if (!$search_cond_chk_result[0]) {
                return $this->createResponse($search_cond_chk_result[1]);
            }
            // フラグセット
            $return_flag = $request->input('return_flag');
            $search_count_only_flag = $request->input('search_count_only_flag');
            $search_condition_return_flag = $request->input('search_condition_return_flag');
        
            // 件数のみ返却
            $search_count = $this->getHospitalCount($request, true);

           // 結果生成
           $status = 0;

            // 件数要素セット
            // page取得の場合、全件件数取得
//            $search_count = $return_flag == 0 ? $entities->count() : $this->getHospitals($request, true);
            $return_count = $search_count;

            $return_from = $return_flag == 0 ? 1 : $request->input('return_from');
            $return_to = $return_flag == 0 ? $search_count : $request->input('return_to');

            $callback = $request->input('callback');

            // 件数のみ返却
            if ($search_count_only_flag == 1) {
                return $search_condition_return_flag == 0 ?
                    response()->json(compact('status', 'search_count', 'return_count', 'return_from', 'return_to'))->setCallback($callback)
                    :
                    response()->json(compact('status', 'search_count', 'return_count', 'return_from', 'return_to') + $request->toJson())->setCallback($callback);
            }

        $targets =  $this->getHospitalCount($request, false);
        $entities = $this->getHospitals($request, $targets);
        $return_count = count($entities);

            // レスポンス生成
            $hospitals = SearchHospitalsResource::collection($entities);
            // response
            return $search_condition_return_flag == 0 ?
                response()->json(compact('status', 'search_count', 'return_count', 'return_from', 'return_to', 'hospitals'))
                    ->setCallback($callback)
                : response()->json(compact('status', 'search_count', 'return_count', 'return_from', 'return_to')
                + $request->toJson()
                + compact('hospitals'))->setCallback($callback);
        } catch (\Exception $e) {
           Log::error($e);
           return $this->createResponse($this->messages['system_error_api'], $request->input('callback'));
        }

    }

    /**
     * 検査コース一覧検索API
     *
     * @param  App\Http\Requests\SearchRequest $request
     * @return \Illuminate\Http\Response
     */
    public function courses(CourseSearchRequest $request)
    {
        try {
            // フラグセット
            $return_flag = $request->input('return_flag');
            $search_count_only_flag = $request->input('search_count_only_flag');
            $search_condition_return_flag = $request->input('search_condition_return_flag');
            $course_price_sort = $request->input('course_price_sort');

            // 件数のみ返却
            $query = $this->getCourseCount($request);

            $search_count = $query->count();
            // limit/offset
            $offset = intval($request->input('return_from')-1);
            $limit = intval($request->input('return_to')) - $offset;
            $query->offset($offset)->limit($limit);
            $targets =  $query->get();
            $entities = $this->getCourses($targets, $course_price_sort);

            // 結果生成
            $status = 0;

            // 件数要素セット
            $return_count = $entities->count();
            $return_from = $return_flag == 0 ? 1 : $request->input('return_from');
            $return_to = $return_flag == 0 ? $search_count : $request->input('return_to');
            
            $callback = $request->input('callback');

            if ($search_count_only_flag == 1) {
                return $search_condition_return_flag == 0 ?
                response()->json(compact('status', 'search_count', 'return_count', 'return_from', 'return_to'))->setCallback($callback)
                    :
                response()->json(compact('status', 'search_count', 'return_count', 'return_from', 'return_to') + $request->toJson())->setCallback($callback);
            }

            // レスポンス生成
            $courses = SearchCoursesResource::collection($entities);

            // response
						Log::info($courses->all());
            return $search_condition_return_flag == 0 ?
                response()->json(compact('status', 'search_count', 'return_count', 'return_from', 'return_to', 'courses'))
                    ->setCallback($callback)
                : response()->json(compact('status', 'search_count', 'return_count', 'return_from', 'return_to')
                + $request->toJson()
                + compact('courses'))->setCallback($callback);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->createResponse($this->messages['system_error_api'], $request->input('callback'));
        }

    }

    /**
     * 医療機関件数取得
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
                ->where('courses.is_category', 0)
                ->where('publish_start_date', '<=', $target)
                ->where('publish_end_date', '>=', $target);
        });

        if (isset($reservation_dt)
            || !empty($request->input('freewords'))
            || !empty($request->input('pref_cd'))
        || !empty($request->input('district_no'))
        || !empty($request->input('rail_no'))
        || !empty($request->input('station_no'))
        || !empty($request->input('course_category_code'))
        || !empty($request->input('exam_type'))
        || !empty($request->input('disease'))
        || !empty($request->input('part'))
        || !empty($request->input('price_upper_limit'))
        || !empty($request->input('price_lower_limit'))
        || !empty($request->input('hospital_category_code'))
        || !empty($request->input('site_card'))) {
            $query->leftJoin('course_metas', 'courses.id', 'course_metas.course_id');
            if (isset($reservation_dt)) {
                $query->leftJoin('calendars', 'calendars.id', 'courses.calendar_id');
                $query->leftJoin('calendar_days', function ($join) use ($target) {
                    $join->on('calendars.id', 'calendar_days.calendar_id')
                        ->where('reservation_frames', '>', 'reservation_count')
                        ->where('is_reservation_acceptance', 0)
                        ->whereDate('calendar_days.date', $target)
                        ->where('is_holiday', 0);
                });
            }

            if (isset($reservation_dt)) {
                $query->whereRaw('? >= DATE_ADD(CURRENT_DATE(), INTERVAL (30 * (reception_start_date DIV 1000) + MOD(reception_start_date, 1000)) DAY) ', [$target]);
                $query->whereDate('calendar_days.date', $target);
            }

            if (!empty($request->input('freewords'))) {
                $freeword_str = str_replace('　', ' ', $request->input('freewords'));
                $freewords = explode(' ', $freeword_str);

                $query->where(function ($q) use ($freewords) {
                    $q->orWhere('hospital_metas.hospital_name', 'like', '%' . $freewords[0] . '%');
                    for ($i = 1; $i < count($freewords); $i++) {
                        $q->orWhere('hospital_metas.hospital_name', 'like', '%' . $freewords[$i] . '%');
                    }

                    $q->orWhere('hospital_metas.course_name', 'like', '%' . $freewords[0] . '%');
                    for ($i = 1; $i < count($freewords); $i++) {
                        $q->orWhere('hospital_metas.course_name', 'like', '%' . $freewords[$i] . '%');
                    }

                    $q->orWhere('hospital_metas.area_station', 'like', '%' . $freewords[0] . '%');
                    for ($i = 1; $i < count($freewords); $i++) {
                        $q->orWhere('hospital_metas.area_station', 'like', '%' . $freewords[$i] . '%');
                    }

                    $q->orWhere('hospital_metas.category_exam_name', 'like', '%' . $freewords[0] . '%');
                    for ($i = 1; $i < count($freewords); $i++) {
                        $q->orWhere('hospital_metas.category_exam_name', 'like', '%' . $freewords[$i] . '%');
                    }

                    $q->orWhere('hospitals.search_word', 'like', "%{$freewords[0]}%");
                    for ($i = 1; $i < count($freewords); $i++){
                    		$q->orWhere('hospitals.search_word', 'like', "%{$freewords[$i]}%");
										}


//                    $q->orWhere('course_metas.category_disease_name', 'like', '%' . $freewords[0] . '%');
//                    for ($i = 1; $i < count($freewords); $i++) {
//                        $q->orWhere('course_metas.category_disease_name', 'like', '%' . $freewords[$i] . '%');
//                    }
                });
            };

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
                $query->whereIn('hospital_metas.district_code', $districts);
            };

            // 路線コード
            $rail_no = $request->input('rail_no');
            if (isset($rail_no)) {
                if (is_array($rail_no)) {
                    $rails = $rail_no;
                } else {
                    $rails = explode(',', $rail_no);
                }
                $query->where(function ($q) use ($rails) {
                    $q->whereIn('hospitals.rail1', $rails)
                        ->orWhereIn('hospitals.rail2', $rails)
                        ->orWhereIn('hospitals.rail3', $rails)
                        ->orWhereIn('hospitals.rail4', $rails)
                        ->orWhereIn('hospitals.rail5', $rails);
                });
            };

            // 駅コード
            $station_no = $request->input('station_no');
            if (isset($station_no)) {
                $stations = explode(',', $station_no);
                $query->where(function ($q) use ($stations) {
                    $q->whereIn('hospitals.station1', $stations)
                        ->orWhereIn('hospitals.station2', $stations)
                        ->orWhereIn('hospitals.station3', $stations)
                        ->orWhereIn('hospitals.station4', $stations)
                        ->orWhereIn('hospitals.station5', $stations);
                });
            };

            // 食事あり
            $meal_flg = false;
            // ペア
            $pear_flg = false;
            // 女性医師
            $female_doctor_flg = false;
            // コース分類コード
            $course_category = $request->input('course_category_code');

            if (isset($course_category)) {
                $course_categories = explode(',', $course_category);
                foreach ($course_categories as $code) {
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

            if ($meal_flg) {
                $query->where('course_metas.meal_flg', 1);
            }
            if ($pear_flg) {
                $query->where('course_metas.pear_flg', 1);
            }
            if ($female_doctor_flg) {
                $query->where('course_metas.female_doctor_flg', 1);
            }

            // 検査種別
            $exam_types = $request->input('exam_type');
            if (isset($exam_types)) {
                $exam_types = explode(',', $exam_types);
                $query->where(function ($q) use ($exam_types, $meal_flg, $pear_flg, $female_doctor_flg) {
                    $q->where('course_metas.category_exam', 'like', '%' . sprintf('%03d',$exam_types[0]) . '%' );
                    for($i = 1; $i < count($exam_types); $i++) {
                        $q->where('course_metas.category_exam', 'like', '%' . sprintf('%03d',$exam_types[$i]) . '%' );
                    }
                });
            }

            // 対象となる疾患
            $diseases = $request->input('disease');
            if (isset($diseases)) {
                $diseases = explode(',', $diseases);
                $query->where(function ($q) use ($diseases, $meal_flg, $pear_flg, $female_doctor_flg) {
                    $q->where('course_metas.category_disease', 'like', '%' . sprintf('%03d',$diseases[0]) . '%' );
                    for($i = 1; $i < count($diseases); $i++) {
                        $q->where('course_metas.category_disease', 'like', '%' . sprintf('%03d',$diseases[$i]) . '%' );
                    }
                });
            }

            // 気になる部位
            $parts = $request->input('part');
            if (isset($parts)) {
                $parts = explode(',', $parts);
                $query->where(function ($q) use ($parts, $meal_flg, $pear_flg, $female_doctor_flg) {
                    $q->where('course_metas.category_part', 'like', '%' . sprintf('%03d',$parts[0]) . '%' );
                    for($i = 1; $i < count($parts); $i++) {
                        $q->where('course_metas.category_part', 'like', '%' . sprintf('%03d',$parts[$i]) . '%' );
                    }
                });
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

            // 現地カード対応
            if (!empty($request->input('site_card'))) {
                $query->where('hospital_metas.credit_card_flg', 1);
            }
        }

        $query->where('hospitals.status', Status::VALID);
        // 並び順
        $query->orderBy('hospitals.pvad', 'desc');
        $query->orderBy('hospitals.pv_count', 'desc');

        // limit/offset
            if (!$count_flg && $request->input("return_flag") != 0) {
                $offset = intval($request->input('return_from')-1);
                $limit = intval($request->input('return_to')) - $offset;
                $query->offset($offset);
                $query->limit($limit);
            }

        $results = $query->get();

        if ($count_flg) {
            return count($results);
        }

        return $results;
    }

    /**
     * コース件数取得
     * @param $request
     * @param $count_flg
     * @return int
     */
    private function getCourseCount($request) {
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

        $query = Course::select('courses.id', 'courses.order', 'courses.price', 'hospitals.pvad', 'hospitals.pv_count')->distinct();
        $query->join('hospitals', function ($join) {
            $join->on('hospitals.id', 'courses.hospital_id')
                ->where('hospitals.status', Status::VALID);
        });
        $query->join('contract_informations', function ($join) {
            $join->on('hospitals.id', 'contract_informations.hospital_id')
                ->whereNotNull('contract_informations.code');
        });

        $query->where('courses.web_reception', WebReception::ACCEPT);
        $query->where('courses.is_category', 0);
        $query->where('publish_start_date', '<=', $target);
        $query ->where('publish_end_date', '>=', $target);

        if (isset($reservation_dt)
            || !empty($request->input('freewords'))
            || !empty($request->input('pref_cd'))
            || !empty($request->input('district_no'))
            || !empty($request->input('rail_no'))
            || !empty($request->input('station_no'))
            || !empty($request->input('course_category_code'))
            || !empty($request->input('exam_type'))
            || !empty($request->input('disease'))
            || !empty($request->input('part'))
            || !empty($request->input('price_upper_limit'))
            || !empty($request->input('price_lower_limit'))
            || !empty($request->input('hospital_category_code'))
            || !empty($request->input('site_card'))) {

            $query->join('hospital_metas', 'hospitals.id', 'hospital_metas.hospital_id');
            $query->leftJoin('course_metas', 'courses.id', 'course_metas.course_id');

            if (isset($reservation_dt)) {
                $query->leftJoin('calendars', 'calendars.id', 'courses.calendar_id');
                $query->leftJoin('calendar_days', function ($join) use ($target) {
                    $join->on('calendars.id', 'calendar_days.calendar_id')
                        ->where('reservation_frames', '>', 'reservation_count')
                        ->where('is_reservation_acceptance', 0)
                        ->whereDate('calendar_days.date', $target)
                        ->where('is_holiday', 0);
                });
            }

            if (isset($reservation_dt)) {
                $query->whereRaw('? >= DATE_ADD(CURRENT_DATE(), INTERVAL (30 * (reception_start_date DIV 1000) + MOD(reception_start_date, 1000)) DAY) ', [$target]);
                $query->whereDate('calendar_days.date', $target);
            }

            if (!empty($request->input('freewords'))) {
                $freeword_str = str_replace('　', ' ', $request->input('freewords'));
                $freewords = explode(' ', $freeword_str);

                foreach($freewords as $freeword) {
                    $query->where(function($q) use($freeword) {
                        $q->orWhere('hospital_metas.hospital_name', 'like', '%'.$freeword.'%');
                        $q->orWhere('hospital_metas.course_name', 'like', '%'.$freeword.'%');
                        $q->orWhere('hospital_metas.area_station', 'like', '%'.$freeword.'%');
                        $q->orWhere('hospital_metas.category_exam_name', 'like', '%'.$freeword.'%');
                        $q->orWhere('hospitals.search_word', 'like', "%{$freeword}%");
//                    $q->orWhere('course_metas.category_disease_name', 'like', '%'.$freeword.'%');
                    });
                }
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
                $query->whereIn('hospital_metas.district_code', $districts);
            };

            // 路線コード
            $rail_no = $request->input('rail_no');
            if (isset($rail_no)) {
                if (is_array($rail_no)) {
                    $rails = $rail_no;
                } else {
                    $rails = explode(',', $rail_no);
                }

                $query->where(function ($q) use ($rails) {
                    $q->whereIn('hospitals.rail1', $rails)
                        ->orWhereIn('hospitals.rail2', $rails)
                        ->orWhereIn('hospitals.rail3', $rails)
                        ->orWhereIn('hospitals.rail4', $rails)
                        ->orWhereIn('hospitals.rail5', $rails);
                });
            };

            // 駅コード
            $station_no = $request->input('station_no');
            if (isset($station_no)) {
                $stations = explode(',', $station_no);
                $query->where(function ($q) use ($stations) {
                    $q->whereIn('hospitals.station1', $stations)
                        ->orWhereIn('hospitals.station2', $stations)
                        ->orWhereIn('hospitals.station3', $stations)
                        ->orWhereIn('hospitals.station4', $stations)
                        ->orWhereIn('hospitals.station5', $stations);
                });
            };

            // 食事あり
            $meal_flg = false;
            // ペア
            $pear_flg = false;
            // 女性医師
            $female_doctor_flg = false;
            // コース分類コード
            $course_category = $request->input('course_category_code');

            if (isset($course_category)) {
                $course_categories = explode(',', $course_category);
                foreach ($course_categories as $code) {
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

            if ($meal_flg) {
                $query->where('course_metas.meal_flg', 1);
            }
            if ($pear_flg) {
                $query->where('course_metas.pear_flg', 1);
            }
            if ($female_doctor_flg) {
                $query->where('course_metas.female_doctor_flg', 1);
            }

            // 検査種別
            $exam_types = $request->input('exam_type');
            if (isset($exam_types)) {
                $exam_types = explode(',', $exam_types);
                $query->where(function ($q) use ($exam_types, $meal_flg, $pear_flg, $female_doctor_flg) {
                    $q->where('course_metas.category_exam', 'like', '%' . sprintf('%03d', $exam_types[0]) . '%' );
                    for($i = 1; $i < count($exam_types); $i++) {
                        $q->where('course_metas.category_exam', 'like', '%' . sprintf('%03d', $exam_types[$i]) . '%' );
                    }
                });
            }

            // 対象となる疾患
            $diseases = $request->input('disease');
            if (isset($diseases)) {
                $diseases = explode(',', $diseases);
                $query->where(function ($q) use ($diseases, $meal_flg, $pear_flg, $female_doctor_flg) {
                    $q->where('course_metas.category_disease', 'like', '%' . sprintf('%03d', $diseases[0]) . '%' );
                    for($i = 1; $i < count($diseases); $i++) {
                        $q->where('course_metas.category_disease', 'like', '%' . sprintf('%03d', $diseases[$i]) . '%' );
                    }
                });
            }

            // 気になる部位
            $parts = $request->input('part');
            if (isset($parts)) {
                $parts = explode(',', $parts);
                $query->where(function ($q) use ($parts, $meal_flg, $pear_flg, $female_doctor_flg) {
                    $q->where('course_metas.category_part', 'like', '%' . sprintf('%03d', $parts[0]) . '%' );
                    for($i = 1; $i < count($parts); $i++) {
                        $q->where('course_metas.category_part', 'like', '%' . sprintf('%03d', $parts[$i]) . '%' );
                    }
                });
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

            // 現地カード対応
            if (!empty($request->input('site_card'))) {
                $query->where('hospital_metas.credit_card_flg', 1);
            }
        }

        // 並び順
        if($request->input('course_price_sort') == 0) {
            $query->orderBy('courses.price');
            $query->orderBy('courses.order');
            $query->orderBy('hospitals.pvad', 'desc');
            $query->orderBy('hospitals.pv_count', 'desc');
        } else {
            $query->orderBy('courses.price', 'desc');
            $query->orderBy('courses.order');
            $query->orderBy('hospitals.pvad', 'desc');
            $query->orderBy('hospitals.pv_count', 'desc');
        }

        return $query;
    }

    /**
     * 医療機関情報取得
     * @param $hospitals
     */
    private function getHospitals($request, $hospitals) {

        $ids = [];
        foreach ($hospitals as $hospital) {
            $ids[] = $hospital->id;
        }

        $target_date = Carbon::today()->toDateString();

        $query =  Hospital::with([
            'hospital_metas',
            'hospital_images',
            'hospital_details' => function ($q) {
                $q->with([
                    'minor_classification'
                ]);
            },
            'hospital_categories' => function ($qu) {
                $qu->whereIn('image_order', [2, 3, 4]);
            },
            'hospital_categories.hospital_image',
            'contract_information',
            'medical_treatment_times',
            'prefecture',
            'district_code',
            'courses' => function ($que) use ($target_date) {
                $que->where('web_reception', WebReception::ACCEPT)
                    ->where('is_category', 0)
                    ->where('publish_start_date', '<=', $target_date)
                    ->where('publish_end_date', '>=', $target_date)
                    ->orderBy('order');
//                    ->with([
//                        'course_details'=> function($query){
//                            $query->with('minor_classification');
//                        },
//                        'calendar',
//                        'calendar_days',
//                        'course_metas',
//                        'course_images',
//                        'hospital',
//                        'contract_information',
//                        'course_options',
//                        'course_options.option',
//                        'course_questions'
//                    ]);
            },
            'courses.course_details',
            'courses.course_details.minor_classification',
            'courses.calendar',
            'courses.course_metas',
            'courses.course_options',
            'courses.course_options.option',
            'courses.course_questions'


        ])
            ->whereIn('hospitals.id', $ids)
            ->orderBy('hospitals.pvad', 'DESC')
            ->orderBy('hospitals.pv_count', 'DESC');

//        if ($request->input('sex')) {
//            $query->with(['courses.kenshin_sys_courses', 'courses.kenshin_sys_courses.course_futan_conditions']);
//        }

        $hospitals = $query->get();

//        $today = Carbon::today();
//
//        if (!empty($request->input('sex'))) {
//            foreach ($hospitals as $hospital) {
//                if (empty($hospital->kenshin_sys_hospital_id) || empty($hospital->courses)) {
//                    continue;
//                }
//
//                foreach ($hospital->courses as $key => $course) {
//                    if (empty($course->kenshin_sys_courses)) {
//                        continue;
//                    }
//                    foreach ($course->kenshin_sys_courses as $kenshin_sys_course) {
//                        if ($kenshin_sys_course->kenshin_sys_riyou_bgn_date > $today
//                            || $kenshin_sys_course->kenshin_sys_riyou_end_date < $today) {
//                            unset($hospital->courses[$key]);
//                            continue;
//                        }
//                        $course_futan_condition = $kenshin_sys_course->course_futan_conditions;
//                        if ($course_futan_condition->sex != GenderTak::ALL
//                            && $course_futan_condition->sex != $request->input('sex')) {
//                            unset($hospital->courses[$key]);
//                            continue;
//                        }
//                        if ($course_futan_condition->honnin_kbn != HonninKbn::ALL
//                            && $course_futan_condition->honnin_kbn != $request->input('honnin_kbn')) {
//                            unset($hospital->courses[$key]);
//                            continue;
//                        }
//                        $age = getAgeTargetDate($request->input('birth'),
//                            null,
//                            $course_futan_condition->kenshin_sys_course_age_kisan_kbn,
//                            $course_futan_condition->kenshin_sys_course_age_kisan_date,
//                            $hospital->medical_examination_system_id);
//
//                        $target_ages = TargetAge::where('course_futan_condition_id', $course_futan_condition->id)
//                            ->get();
//
//                        if ($target_ages) {
//                            $exist_flg = false;
//                            foreach ($target_ages as $target_age) {
//                                if ($target_age->target_age == $age) {
//                                    $exist_flg = true;
//                                    break;
//                                }
//                            }
//                            if (!$exist_flg) {
//                                unset($hospital->courses[$key]);
//                                continue;
//                            }
//                        }
//                    }
//
//                }
//            }
//        }

        return $hospitals;
    }

    /**
     * コース情報取得
     * @param $courses
     * @param $course_price_sort
     * @return Course[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    private function getCourses($courses, $course_price_sort) {

        $query = Course::whereIn('id', $courses->pluck('id'))
            ->with([
//                'course_metas',
                'course_details' => function ($query) {
                    $query->whereIn('major_classification_id', [1, 2, 3, 4, 5, 6, 11, 13, 24])->with(['minor_classification']);
                },
                'course_options',
                'course_options.option',
                'course_questions',
                'course_images',
                'calendar',
                'calendar_days',
                'hospital',
//                'hospital_metas',
                'contract_information',
            ]);
        if($course_price_sort == 0) {
            $query->orderBy('price')->orderBy('order');
        } else {
            $query->orderBy('price', 'desc')->orderBy('order');
        }
        return $query->get();
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
