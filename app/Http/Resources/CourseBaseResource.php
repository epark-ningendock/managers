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
            'cancellation_deadline' => $this->cancellation_deadline ?? 0,
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
            'options' => $this->getOptions(),
            'question' => $this->getQuestion(),
            'kenshin_relation_flg' => $this->kenshin_relation_flg ?? 0,
            'kenshin_sys_dantai_no' => $this->getKenshinSysInfo()[0],
            'kenshin_sys_course_no' => $this->getKenshinSysInfo()[1]
        ]);
    }

    private function getKenshinSysInfo() {
        if ($this->kenshin_relation_flg
            && !empty($this->kenshin_sys_courses)
            && count($this->kenshin_sys_courses) > 0) {
            return [$this->kenshin_sys_courses[0]->kenshin_sys_dantai_no, $this->kenshin_sys_courses[0]->kenshin_sys_course_no];
        }

        return ['', ''];
    }

    /**
     * @return array
     */
    private function getOptions() {
        $results = [];
        if ($this->kenshin_relation_flg) {
            if (!empty($this->kenshin_sys_courses)
                && count($this->kenshin_sys_courses) > 0
                && !empty($this->kenshin_sys_courses[0]->kenshin_sys_options)
                && count($this->kenshin_sys_courses[0]->kenshin_sys_options) > 0) {
                $kenshin_sys_options = $this->kenshin_sys_courses[0]->kenshin_sys_options;

                foreach ($kenshin_sys_options as $option) {
                    if (empty($option->option_futan_conditions)) {
                        continue;
                    }
                    $option_futan_conditions = $option->option_futan_conditions;
                    foreach ($option_futan_conditions as $c) {
                        if (empty($c->optionn_target_ages)) {
                            $results[] = ['cd' => $option->id,
                                'title' => $option->kenshin_sys_option_name,
                                'confirm' => '',
                                'price' => $c->futan_kingaku];
                        } else {
                            $target_date = getAgeTargetDate($this->birth,
                                $this->reservation_date,
                                $option->kenshin_sys_option_age_kisan_kbn,
                                $option->kenshin_sys_option_age_kisan_date,
                                $this->medical_exam_sys_id);
                            $age = calcAge($this->birth, $target_date);
                            foreach ($c->optionn_target_ages as $target_age) {
                                if ($age == $target_age->target_age) {
                                    $results[] = ['cd' => $option->id,
                                        'title' => $option->kenshin_sys_option_name,
                                        'confirm' => '',
                                        'price' => $c->futan_kingaku];
                                }
                            }
                        }
                    }
                }

            }
        } else {
            foreach ($this->course_options as $course_option) {
                $option = $course_option->option;
                if (!$option || empty($option->id)) {
                    continue;
                }
                $results[] = ['cd' => $option->id, 'title' => $option->name, 'confirm' => $option->confirm, 'price' => $option->price];
            }
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

            $results[] = ['no' => $question->question_number, 'text' => $question->question_title, 'answer' => $answer];
        }

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
