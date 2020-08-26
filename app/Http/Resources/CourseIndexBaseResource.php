<?php

namespace App\Http\Resources;

use App\Enums\CalendarDisplay;
use App\Enums\Status;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;

use App\Enums\WebReception;

use Log;

class CourseIndexBaseResource extends Resource
{
    /**
     * 検査コース基本情報 resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->baseCollections()->toArray();
    }

    /**
     * 検査コース基本情報
     *
     * @return Illuminate\Support\Collection
     */
    protected function baseCollections()
    {
        return collect(
            [
                'course_no' => $this->id,
                'course_code' => $this->code,
                'course_name' => $this->name,
                'course_url' => $this->createURL() . "/detail_hospital/" . $this->contract_information->code . "/detail/" . $this->code . ".html",
                'web_reception' => $this->createReception(),
                'course_flg_category' => $this->is_category,
                'course_img' => $this->getCourseImg($this->course_images),
                'flg_price' => $this->is_price,
                'price' => $this->price,
                'flg_price_memo' => $this->is_price_memo,
                'price_memo' => $this->price_memo ?? '',
                'course_point' => $this->course_point,
                'course_notice' => $this->course_notice,
                'course_cancel' => $this->course_cancel,
                'time_required' => $this->getContents()[0],
                'stay' => $this->getContents()[1],
                'meal' => $this->getContents()[2],
                'week_end' => $this->getContents()[3],
                'exam_start' => $this->getContents()[4],
                'result_examination' => $this->getContents()[5],
                'category_disease' => $this->getContents()[6],
                'category_exam' => $this->_categories($this->course_details),
                'category_recommended' => $this->getContents()[7],
                'options' => $this->getOptions(),
                'question' => $this->getQuestion(),
                'pre_account_price' => $this->pre_account_price,
                'flg_local_payment' => $this->is_local_payment,
                'flg_pre_account' => $this->is_pre_account,
                'auto_calc_application' => $this->auto_calc_application,
                'all_calender' => new DailyCalendarResource($this)
            ]
        );
    }

    /**
     * @return array
     */
    private function getOptions() {
        $results = [];
        foreach ($this->course_options as $course_option) {
            $option = $course_option->option;
            $results[] = ['cd' => $option->id, 'title' => $option->name, 'confirm' => $option->confirm, 'price' => $option->price];
        }

        return $results;
    }

    /**
     * @return array
     */
    private function getQuestion() {

        $results = [];
        foreach ($this->course_questions as $question) {
            if ($question->is_question == 1) {
                continue;
            }

            $answer = [];
            if (isset($question->answer01)) {
                $answer[] = ['no' => 1, 'text' => $question->answer01];
            }
            if (isset($question->answer02)) {
                $answer[] = ['no' => 2, 'text' => $question->answer02];
            }
            if (isset($question->answer03)) {
                $answer[] = ['no' => 3, 'text' => $question->answer03];
            }
            if (isset($question->answer04)) {
                $answer[] = ['no' => 4, 'text' => $question->answer04];
            }
            if (isset($question->answer05)) {
                $answer[] = ['no' => 5, 'text' => $question->answer05];
            }
            if (isset($question->answer06)) {
                $answer[] = ['no' => 6, 'text' => $question->answer06];
            }
            if (isset($question->answer07)) {
                $answer[] = ['no' => 7, 'text' => $question->answer07];
            }
            if (isset($question->answer08)) {
                $answer[] = ['no' => 8, 'text' => $question->answer08];
            }
            if (isset($question->answer09)) {
                $answer[] = ['no' => 9, 'text' => $question->answer09];
            }
            if (isset($question->answer10)) {
                $answer[] = ['no' => 10, 'text' => $question->answer10];
            }

            $results[] = ['id' => $question->id, 'no' => $question->question_number, 'text' => $question->question_title, 'answer' => $answer];
        }

        return $results;
    }

    /**
     * コースカテゴリ情報生成
     *
     * @param  コース詳細
     * @return カテゴリ
     */
    private function _categories($course_details)
    {
        $results = [];
        $major_ids = [];
        $middle_ids = [];
        $minor_ids = [];
        $major_id = 0;
        $middle_id = 0;
        $major_title = '';
        $middle_title = '';
        $i = 0;
        $d = null;
        foreach ($course_details as $detail) {

            if ($detail->major_classification_id != 2
                && $detail->major_classification_id != 3
                && $detail->major_classification_id != 4
                && $detail->major_classification_id != 5
                && $detail->major_classification_id != 6) {
                continue;
            }

            if ($detail->select_status != 1 || $detail->status != '1') {
                continue;
            }

            if ($detail->minor_classification->is_fregist == '1') {
                $name = $detail->minor_classification->name;
            } else {
                $name = $detail->inputstring ?? '';
            }

            if (isset($d) && $d->major_classification_id != $detail->major_classification_id) {
                if ($i == 0) {
                    $minor_ids[] = ['minor_id' => $detail->minor_classification_id,
                        'minor_title' => $name];
                } else {
                    $middle_ids[] = [
                        'middle_title' => $d->middle_classification->name,
                        'middle_id' => $d->middle_classification_id,
                        'category_small' => $minor_ids];
                    $major_ids[] = [
                        'major_title' => $d->major_classification->name,
                        'major_id' => $d->major_classification_id,
                        'category_middle' => $middle_ids];
                    $middle_ids = [];
                    $minor_ids = [];
                    $minor_ids[] = ['minor_id' => $detail->minor_classification_id,
                        'minor_title' => $name];
                }

                $minor_ids = [];
                $middle_ids = [];
            } elseif (isset($d) && $d->middle_classification_id != $detail->middle_classification_id) {
                $middle_ids[] = [
                    'middle_title' => $d->middle_classification->name,
                    'middle_id' => $d->middle_classification_id,
                    'category_small' => $minor_ids];
                $minor_ids = [];
                $minor_ids[] = [
                    'minor_title' => $name,
                    'minor_id' => $detail->minor_classification_id];
            } else {
                $minor_ids[] = [
                    'minor_title' => $name,
                    'minor_id' => $detail->minor_classification_id];
            }

            $i++;
            $d = $detail;
        }

        $middle_ids[] = [
            'middle_title' => $d->middle_classification->name,
            'middle_id' => $d->middle_classification_id,
            'category_small' => $minor_ids];
        $major_ids[] = [
            'major_title' => $d->major_classification->name,
            'major_id' => $d->major_classification_id,
            'category_middle' => $middle_ids];

        return $major_ids;
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
     * @return array
     */
    private function getContents() {

        $time_required = '';
        $stay = '';
        $meal = '';
        $week_end = '';
        $exam_start = '';
        $result_examination = '';
        $category_disease = '';
        $recommend = [];
        foreach ($this->course_details as $detail) {

            if ($detail->major_classification_id == 15) {
                $time_required = $detail->inputstring ?? '';
            }

            if ($detail->major_classification_id == 17) {
                $stay = $detail->inputstring ?? '';
            }

            if ($detail->major_classification_id == 18) {
                $meal = $detail->inputstring ?? '';
            }

            if ($detail->major_classification_id == 20) {
                $week_end = $detail->inputstring ?? '';
            }

            if ($detail->major_classification_id == 16) {
                $exam_start = $detail->inputstring ?? '';
            }

            if ($detail->major_classification_id == 19) {
                $result_examination = $detail->inputstring ?? '';
            }

            if ($detail->major_classification_id == 25 && $detail->select_status == 1) {
                $category_disease = $category_disease . $detail->minor_classification->name . '、';
            }

            if ($detail->major_classification_id == 24 && isset($detail->inputstring)) {
                $recommend[] = $detail->inputstring;
            }
        }

        $category_disease = rtrim($category_disease, '、');

        return [$time_required, $stay, $meal, $week_end, $exam_start, $result_examination, $category_disease, $recommend];
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

    private function createURL() {
        return (empty($_SERVER['HTTPS']) ? 'http://' : 'https://').$_SERVER['HTTP_HOST'];
    }
}
