<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\SearchRequest;
use App\Http\Requests\HospitalSearchRequest;
use App\Http\Controllers\Controller;

use App\Hospital;
use App\Course;

use App\Http\Resources\SearchHospitalsResource;
use App\Http\Resources\SearchCoursesResource;

class SearchController extends Controller
{
    /**
     * 医療機関・検査コース一覧検索API
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(SearchRequest $request)
    {
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
    }

    /**
     * 医療機関一覧検索API
     *
     * @param  App\Http\Requests\HospitalSearchRequest $request
     * @return \Illuminate\Http\Response
     */
    public function hospitals(HospitalSearchRequest $request)
    {
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
    }

    /**
     * 検査コース一覧検索API
     *
     * @param  App\Http\Requests\SearchRequest $request
     * @return \Illuminate\Http\Response
     */
    public function courses(SearchRequest $request)
    {
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
                'hospital_details.hospital_minor_classification',
                'hospital_details.hospital_minor_classification.hospital_major_classification',
                'hospital_details.hospital_minor_classification.hospital_middle_classification',
                'hospital_categories',
                'hospital_categories.image_order',
                'hospital_categories.hospital_image',
                'districtCode',
                'prefecture',
            ])
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
        $query = Course::query()
            ->with([
                'course_meta_informations',
                'course_details',
                'course_details.major_classification',
                'course_details.middle_classification',
                'course_details.minor_classification',
                'course_options',
                'calendar_days',
                'course_images',
                'course_images.image_order',
                'course_images.hospital_image',
                'hospital',
                'hospital.contract_information',
                'hospital.hospital_categories',
            ])
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
