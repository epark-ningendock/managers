<?php

namespace App\Http\Resources;

use App\Enums\CalendarDisplay;
use App\Enums\WebReception;
use Carbon\Carbon;

class CourseIndexResource extends CourseIndexBaseResource
{
    /**
     * 検査コース情報 resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $hospital = $this['hospital'];
        $course = $this['course'];
        $courses = $this['courses'];

        return collect([])
            ->merge(new HospitalBasicResource($hospital))
            ->merge(new CourseIndexBaseResource($course))
            ->put('courses', $this->getCourses($courses))
            ->toArray();
    }

    /**
     *
     */
    private function getCourses($courses) {

        $results = [];
        foreach ($courses as $course) {
            $course = [
                'course_no' => $course->id,
                'course_code' => $course->code,
                'course_name' => $course->name,
                'course_url' => $this->createURL() . "/detail_hospital/" . $course->contract_information->code . "/detail/" . $course->code . ".html",
                'web_reception' => $this->createReception($course),
                'course_img' => $this->getCourseImg($course->course_images),
                'flg_price' => $course->is_price,
                'price' => $course->price,
                'flg_price_memo' => $course->is_price_memo,
                'price_memo' => $course->price_memo,
                'course_point' => $course->course_point,
                'category_exam' => $this->getCategoryType($course)
            ];

            $results[] = $course;
        }

        return $results;
    }

    /**
     * @return array
     */
    private function getCategoryType($course) {

        $results = [];
        $sort_key = [];

        foreach ($course->course_details as $detail) {
            if ($detail->major_classification_id == 13
                && $detail->select_status == 1
                && $detail->status == '1'
            ) {
                $result = ['id' => $detail->minor_classification_id, 'title' => $detail->minor_classification->name];
                $results[] = $result;
                $sort_key[] = $detail->minor_classification_id;
            }
        }

        $sort_key = array_unique($sort_key);
        $results = array_unique($results, SORT_REGULAR);
        array_multisort($sort_key, SORT_NATURAL, $results);
        return $results;
    }

    /**
     * サブメイン画像取得
     *
     * @param  医療機関カテゴリ
     * @return パス
     */
    protected function getCourseImg($course_images)
    {
        if (!isset($course_images)) return '';

        foreach ($course_images as $course_image) {
            if ($course_image->type == '0') {
                return $course_image->path;
            }
        }

        return '';
    }

    /**
     * @return int
     */
    private function createReception($course)
    {
        if ($course->web_reception == strval(WebReception::NOT_ACCEPT)) {
            return WebReception::NOT_ACCEPT;
        }

        $target = Carbon::today();
        if (($course->publish_start_date != null &&
                $course->publish_start_date > $target)
            || ($course->publish_end_date != null &&
                $course->publish_end_date < $target)) {
            return WebReception::NOT_ACCEPT;
        }

        if (isset($course->calendar) && $course->calendar->is_calendar_display == strval(CalendarDisplay::HIDE)) {
            return WebReception::ACCEPT_HIDE_CALENDAR;
        }

        return WebReception::ACCEPT;
    }

    private function createURL() {
        return (empty($_SERVER['HTTPS']) ? 'http://' : 'https://').$_SERVER['HTTP_HOST'];
    }
}
