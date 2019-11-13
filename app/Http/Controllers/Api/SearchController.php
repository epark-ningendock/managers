<?php

namespace App\Http\Controllers\Api;

use App\Enums\Status;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\HospitalSearchRequest;

use App\Hospital;
use App\Course;

use App\Http\Resources\SearchHospitalsResource;
use App\Http\Resources\SearchCoursesResource;
use Carbon\Carbon;
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
        try {
            $search_cond_chk_result = $this->checkSearchCond($request, true);
            if (!$search_cond_chk_result[0]) {
                return $this->createResponse($search_cond_chk_result[1]);
            }
            // フラグセット
            $return_flag = $request->input('return_flag');
            $search_count_only_flag = $request->input('search_count_only_flag');
            $search_condition_return_flag = $request->input('search_condition_return_flag');

            // 対象データ取得
            $entities = $this->getHospitals($request);

            // 結果生成
            $status = 0;

            // 件数要素セット
            // page取得の場合、全件件数取得
            $search_count = $return_flag == 0 ? $entities->count() : $this->getHospitals($request, true);
            $return_count = $entities->count();
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
        } catch (\Exception $e) {
            Log::error($e);
            return $this->createResponse($this->messages['system_error_api']);
        }

    }

    /**
     * 検査コース一覧検索API
     *
     * @param  App\Http\Requests\SearchRequest $request
     * @return \Illuminate\Http\Response
     */
    public function courses(SearchRequest $request)
    {
        try {
            $search_cond_chk_result = $this->checkSearchCond($request, false);
            if (!$search_cond_chk_result[0]) {
                return $this->createResponse($search_cond_chk_result[1]);
            }
            // フラグセット
            $return_flag = $request->input('return_flag');
            $search_condition_return_flag = $request->input('search_condition_return_flag');

            // 対象データ取得
            $entities = $this->getCourses($request);

            // 結果生成
            $status = 0;

            // 件数要素セット
            // page取得の場合、全件件数取得
            $search_count = $return_flag == 0 ? $entities->count() : $this->getCourses($request, true);
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
        } catch (\Exception $e) {
            Log::error($e);
            return $this->createResponse($this->messages['system_error_api']);
        }

    }

    /**
     * 医療機関データ取得
     *
     * @param  App\Http\Requests\SearchRequest  $request
     * @param  bool                             $isCount 件数のみ:true
     * @return Illuminate\Support\Collection    検索結果
     */
    private function getHospitals($request, $isCount = false)
    {
        $query = Hospital::query()
            ->with([
                'courses',
                'courses.course_meta_informations',
                'courses.course_details',
                'courses.calendar_days',
                'contract_information',
                'hospital_details',
                'hospital_details.minor_classification',
                'hospital_details.minor_classification.major_classification',
                'hospital_details.minor_classification.middle_classification',
                'hospital_categories',
                'hospital_categories.hospital_image',
                'district_code',
                'prefecture',
            ])
            ->whereHas('contract_information' , function($q) {
                $q->whereNotNull('contract_informations.code');
            })
            ->whereHas('courses' , function($q) {
                $q->where('courses.status', Status::VALID);
            })
            ->where('hospitals.status', Status::VALID)

            ->whereForSearchAPI($request);

        // 件数のみ
        if ($isCount === true) return $query->count();

        // limit/offset
        if ($request->input('return_flag') == 1) {
            $offset = intval($request->input('return_from')-1);
            $limit = intval($request->input('return_to')) - $offset;
            $query->offset($offset)->limit($limit);
        }
        // string_limit_sizeの追加
        $entities = $query->get();
        foreach ($entities as $e) {
            $e['string_limit_size'] = intval($request->input('string_limit_size'));
        }
        return $entities;
    }

    /**
     * 検査コースデータ取得
     *
     * @param  App\Http\Requests\SearchRequest  $request
     * @param  bool                             $isCount 件数のみ:true
     * @return Illuminate\Support\Collection    検索結果
     */
    private function getCourses($request, $isCount = false)
    {
        $from = Carbon::today()->format("Y-m-s");
        $to = Carbon::today()->addMonthsNoOverflow(2)->endOfMonth();
        $query = Course::query()
            ->with([
                'course_meta_informations',
                'course_details',
                'course_details.major_classification',
                'course_details.middle_classification',
                'course_details.minor_classification',
                'course_options',
                'calendar_days'
                    => function($q) use ($from, $to) {
                    $q->where('date', '>=', $from)
                        ->where('date', '<=', $to);
                },
                'course_images',
                'course_images.image_order',
                'course_images.hospital_image',
                'hospital',
                'contract_information',
                'hospital.hospital_categories',
            ])
            ->whereHas('contract_information' , function($q) {
                $q->whereNotNull('contract_informations.code');
            })
            ->whereHas('hospital' , function($q) {
                $q->where('hospitals.status', Status::VALID);
            })
            ->where('courses.status', Status::VALID)
            ->whereForSearchAPI($request);

        // 件数のみ
        if ($isCount === true) return $query->count();

        // limit/offset
        if ($request->input('return_flag') == 1) {
            $offset = intval($request->input('return_from')-1);
            $limit = intval($request->input('return_to')) - $offset;
            $query->offset($offset)->limit($limit);
        }
        // string_limit_sizeの追加
        $entities = $query->get();
        foreach ($entities as $e) {
            $e['string_limit_size'] = intval($request->input('string_limit_size'));
        }
        return $entities;
    }
}
