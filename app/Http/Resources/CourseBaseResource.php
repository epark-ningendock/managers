<?php

namespace App\Http\Resources;

use App\Enums\CalendarDisplay;
use App\Enums\Status;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;

use App\Enums\WebReception;

use Log;

class CourseBaseResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->baseCollections()->toArray();
    }

    /**
     * 検査コース共通情報
     *
     * @return Illuminate\Support\Collection
     */
    protected function baseCollections()
    {
        return collect([
            'course_no' => $this->id,
            'course_code' => $this->code,
            'course_name' => $this->name,
            'course_url' => $this->createURL() . "/detail_hospital/" . $this->contract_information->code . "/detail/" . $this->code . ".html",
            'sho_name' => $this->getShoName(),
            'web_reception' => $this->createReception(),
            'course_flg_category' => $this->is_category,
            'course_img' => $this->getCourseImg($this->course_images),
            'course_point' => $this->course_point ?? '',
            'course_notice' => $this->course_notice ?? '',
            'course_cancel' => $this->course_cancel ?? '',
            'flg_price' => $this->is_price,
            'price' => $this->price,
            'flg_price_memo' => $this->is_price_memo ?? '',
            'price_memo' => $this->price_memo ?? '',
            'pre_account_price' => $this->pre_account_price ?? '',
            'flg_local_payment' => $this->is_local_payment,
            'flg_pre_account' => $this->is_pre_account,
            'auto_calc_application' => $this->auto_calc_application,
        ]);
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
    private function createReception()
    {

        if ($this->web_reception == strval(WebReception::NOT_ACCEPT)) {
            return WebReception::NOT_ACCEPT;
        }

        $target = Carbon::today();
        if (($this->publish_start_date != null &&
                $this->publish_start_date > $target)
            || ($this->publish_end_date != null &&
                $this->publish_end_date < $target)) {
            return WebReception::NOT_ACCEPT;
        }

        if (isset($this->calendar) && $this->calendar->is_calendar_display == strval(CalendarDisplay::HIDE)) {
            return WebReception::ACCEPT_HIDE_CALENDAR;
        }

        return WebReception::ACCEPT;
    }

    private function getShoName() {

        $result = [];
        if (!isset($this->course_metas)) {
            return '';
        }
        $category_exam = explode(' ', $this->course_metas->category_exam_name);
        $category_disease = explode(' ', $this->course_metas->category_disease_name);
        $category_part = explode(' ', $this->course_metas->category_part_name);

        foreach ($category_exam as $exam) {
            $result[] = $exam;
        }

        foreach ($category_disease as $disease) {
            $result[] = $disease;
        }

        foreach ($category_part as $part) {
            $result[] = $part;
        }

        return $result;
    }

    private function createURL() {
        return (empty($_SERVER['HTTPS']) ? 'http://' : 'https://').$_SERVER['HTTP_HOST'];
    }
}
