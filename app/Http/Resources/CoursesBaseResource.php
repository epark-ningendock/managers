<?php

namespace App\Http\Resources;

use App\Enums\CalendarDisplay;
use App\Enums\Status;
use App\Station;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;

use App\Reservation;
use App\Holiday;
use App\Enums\WebReception;
use phpDocumentor\Reflection\Types\Parent_;

class CoursesBaseResource extends CourseBaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->baseCollections();
    }

    /**
     * 検査コース共通情報
     *
     * @return Illuminate\Support\Collection
     */
    protected function baseCollections()
    {
        return
            parent::baseCollections()
                ->put('hospital', $this->getHospital())
                ->put('course_point', $this->getPoint())
                ->put('category', $this->getCategory())
                ->put('exams', $this->getExam())
                ->put('feature', $this->getFeature())
                ->put('require_time', $this->getRequireTime())
                ->put('result', $this->getResult())
                ->put('recommended', $this->getCategoryRecommend())
                ->put('course_option_flag',  isset($this->course_options) ? 1 : 0)
                ->put('month_calender', new MonthlyCalendarResource($this))
                ->put('all_calender', new CalendarDailyResource($this))
                ->toArray();
    }

    private function getHospital() {
        $h = $this->hospital;
        $rails = [$h->rail1, $h->rail2, $h->rail3, $h->rail4, $h->rail5];
        $stations = [$h->station1, $h->station2, $h->station3, $h->station4, $h->station5];
        $accesses = [$h->access1, $h->access2, $h->access3, $h->access4, $h->access5];
        return [
            'no' => $h->id,
            'name' => $h->name,
            'pref_name' => $h->prefecture->name,
            'district_name' => $h->districtCode->name,
            'address1' => $h->address1 ?? '',
            'address2' => $h->address2 ?? '',
            'stations' => Station::getStations($rails, $stations, $accesses)
        ];
    }

    private function getPoint() {
        if (strlen($this->course_point) > 254) {
            return mb_strcut($this->course_point, 0 , 254, 'UTF-8') . '...';
        } else {
            return $this->course_point ?? '';
        }
    }

    /**
     * @return string
     */
    private function getResult() {
        foreach ($this->course_details as $detail) {
            if ($detail->major_classification_id == 19 && !empty($detail->inputstring)) {
                return [$detail->inputstring];
            }
        }

        return [];
    }

    /**
     * 検査の所要時間
     * @return string
     */
    private function getRequireTime() {
        foreach ($this->course_details as $detail) {
            if ($detail->major_classification_id == 15 && !empty($detail->inputstring)) {
                return [$detail->inputstring];
            }
        }

        return [];
    }

    /**
     * @return array
     */
    private function getExam() {

        $results = [];

        foreach ($this->course_details as $detail) {
            if (in_array($detail->major_classification_id, array(2, 3, 4, 5, 6))
                && $detail->select_status == 1
                && $detail->status == '1'
                && !empty($detail->minor_classification->icon_name)
            ) {
                if (in_array($detail->minor_classification->icon_name, $results)) {
                    continue;
                }
                $results[] = $detail->minor_classification->icon_name;
            }
        }
        return $results;
    }

    /**
     * 検索の特徴アイコン
     * @return array
     */
    private function getFeature() {
        $results = [];

        foreach ($this->course_details as $detail) {
            if (in_array($detail->major_classification_id, array(11))
                && $detail->select_status == 1
                && $detail->status == '1'
                && !empty($detail->minor_classification->icon_name)
            ) {
                if (in_array($detail->minor_classification->icon_name, $results)) {
                    continue;
                }
                $results[] = $detail->minor_classification->icon_name;
            }
        }

        $results = array_unique($results, SORT_REGULAR);
        return $results;
    }

    /**
     * @return string
     */
    private function getResultExamination() {

        $results = [];
        foreach ($this->course_details as $detail) {
            if ($detail->major_classification_id == 19) {
                $results[] = ['id' => $detail->minor_classification_id,
                    'title' => $detail->inputstring ?? '',
                    'text' => $detail->inputstring ?? ''];
            }
        }

        return $results;
    }

    /**
     * @return array
     */
    private function getCategory() {

        $results = [];
        $category_chara = $this->getCategoryChara();
        $category_content = $this->getCategoryContent();
        $category_type = $this->getCategoryType();
        $category_result_examination = $this->getResultExamination();

        foreach ($category_chara as $c) {
            $results[] = $c;
        }

        foreach ($category_content as $c) {
            $results[] = $c;
        }

        foreach ($category_type as $c) {
            $results[] = $c;
        }

        foreach ($category_result_examination as $c) {
            $results[] = $c;
        }

        return $results;
    }

    /**
     * @return array
     */
    private function getCategoryChara() {

        $results = [];
        foreach ($this->course_details as $detail) {
            if ($detail->major_classification_id == 11
                && $detail->select_status == 1
                && $detail->status == '1'
                && $detail->minor_classification->is_icon == '1') {
                $result = ['id' => $detail->minor_classification_id,
                    'title' => $detail->minor_classification->icon_name,
                    'text' => $detail->minor_classification->name];
                $results[] = $result;
            }
        }

        return $results;
    }

    /**
     * @return array
     */
    private function getCategoryContent() {

        $results = [];
        foreach ($this->course_details as $detail) {
            if (($detail->major_classification_id == 2
                    || $detail->major_classification_id == 3
                    || $detail->major_classification_id == 4
                    || $detail->major_classification_id == 5
                    || $detail->major_classification_id == 6)
                && $detail->select_status == 1
                && $detail->status == '1'
                && $detail->minor_classification->is_icon == '1') {
                $result = ['id' => $detail->minor_classification_id,
                    'title' => $detail->minor_classification->icon_name,
                    'text' => $detail->minor_classification->name];
                $results[] = $result;
            }
        }
        return array_unique($results, SORT_REGULAR);
    }

    /**
     * @return array
     */
    private function getCategoryRecommend() {

        $results = [];
        foreach ($this->course_details as $detail) {
            if ($detail->major_classification_id == 24
                && !empty($detail->inputstring))
                 {
                $results[] = $detail->inputstring;
            }
        }

        return $results;
    }

    /**
     * @return array
     */
    private function getCategoryType() {

        $results = [];
        foreach ($this->course_details as $detail) {
            if ($detail->major_classification_id == 13
                && $detail->select_status == 1
                && $detail->status == '1') {
                $result = ['id' => $detail->minor_classification_id,
                    'title' => $detail->minor_classification->name,
                    'text' => $detail->minor_classification->name];
                $results[] = $result;
            }
        }

        return $results;
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

    /**
     * 検査コースのカレンダー（日）要素追加
     *
     * @param  検査コース情報  $courses
     * @return 作成カレンダー
     */
    public static function modifyCalendarDays($courses)
    {
        foreach ($courses->calendar_days as $c) {

            // 日毎受付可否情報
            $day = intval(date('Ymd', strtotime($c->date)));
            if ($day < $courses->reception_start_date)
                $c['appoint_status'] = 1; // 受付開始前
            else if ($day > $courses->reception_end_date || $c->reservation_frames <= $c->reservation_count)
                $c['appoint_status'] = 2; // 受付終了
            else if ($c->is_reservation_acceptance === 0)
                $c['appoint_status'] = 3; // 受付不可
            else if ($c->is_holiday === 1)
                $c['appoint_status'] = 3; // 受付不可
            else
                $c['appoint_status'] = 0; // 受付可能

            // 既予約数取得
            $c['appoint_num'] = $c->reservation_count;

            // 休診日
            $c['closed_day'] = Holiday::where('hospital_id', $courses->hospital_id)
                ->whereDate('date', $c->date)->count();
        }

        return $courses;
    }

    /**
     * サブメイン画像取得
     *
     * @param  医療機関カテゴリ
     * @return サブメイン画像
     */
    protected function getCourseImg($course_images)
    {
        if (!isset($course_images)) return [];

        foreach ($course_images as $course_image) {
            if ($course_image->type == '0') {
                return [
                    'url' => $course_image->path ,
                    'alt' => '',
                ];
            }
        }

        return [];
    }

    /**
     * テキスト短縮
     *
     * @param  対象テキストデータ
     * @return 検索条件要素
     */
    protected function wrapWord($text)
    {
        return intval($this->string_limit_size) > 0 ?
            $this->mb_strimwidth(strip_tags($text), 0, $this->string_limit_size, "…", "UTF-8") : $text;
    }

    /**
     *
     * 文字列丸め＆付与処理
     *
     * @param	string	$sString		丸め対象文字列
     * @param	integer	$nStart			丸め開始位置
     * @param	integer	$nWidth			丸め幅（半角1バイト、全角2バイト）
     * @param	string	$sTrimmarker	付与文字列
     * @param	integer	$nEncoding		文字コード
     * @return	string					処理後文字列
     *
     **/
    private function mb_strimwidth($sString, $nStart, $nWidth, $sTrimmarker = "", $nEncoding = null)
    {
        if (is_null($nEncoding)) { // 文字コード設定
            $nEncoding = mb_internal_encoding();
        }
        // 対象文字列を指定文字コードで全取得
        $sString = mb_substr($sString, $nStart, null, $nEncoding);
        // 対象文字列をSJISに変換
        $sConvString = mb_convert_encoding($sString, "SJIS-win", $nEncoding);
        // 付与文字をSJISに変換
        $sConvTrimmarker = mb_convert_encoding($sTrimmarker, "SJIS-win", $nEncoding);
        if (strlen($sConvString) > $nWidth) {
            // 丸めあり
            $nStrcutWidth = $nWidth - strlen($sTrimmarker);
            if ($nStrcutWidth < 0) {
                $nStrcutWidth = 0;
            } else if (($nStrcutWidth % 2) != 0) {
                // 奇数（バイト）
                $nStrcutWidth -= 1;
            }
            $sResult = mb_strcut($sConvString, 0, $nStrcutWidth, "SJIS-win") . $sConvTrimmarker;
            return (mb_convert_encoding($sResult, $nEncoding, "SJIS-win"));
        } else { // 丸めなし
            return ($sString);
        }
    }

    private function createURL() {
        return (empty($_SERVER['HTTPS']) ? 'http://' : 'https://').$_SERVER['HTTP_HOST'];
    }
}
