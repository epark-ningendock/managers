<?php

namespace App\Http\Resources;

use App\Enums\CalendarDisplay;
use App\Enums\Status;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;

use App\Reservation;
use App\Holiday;
use App\Enums\WebReception;

class CoursesBaseResource extends Resource
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
            'sho_name' => $this->createShoname(),
            'web_reception' => $this->createReception(),
            'flg_price' => $this->is_price,
            'price' => $this->price,
            'flg_price_memo' => $this->is_price_memo,
            'price_memo' => $this->price_memo,
            'price_2' => $this->regular_price,
            'price_3' => $this->discounted_price,
            'tax_class' => $this->tax_class_id,
            'pre_account_price' => $this->pre_account_price,
            'flg_local_payment' => $this->is_local_payment,
            'flg_pre_account' => $this->is_pre_account,
            'auto_calc_application' => $this->auto_calc_application,
        ]);
    }

    /**
     * @return string
     *
     */
    private function createShoname() {
        $result = '';
        foreach ($this->course_details as $detail) {
            if ($detail->major_classification_id == 13
                && $detail->middle_classification_id == 30
            && $detail->select_status == 1
            && $detail->status == Status::VALID) {
                $result = $result . $detail->minor_classification->name . ',';
            }
        }

        if (!empty($result)) {
            return rtrim($result, ',');
        }

        return $result;
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
        if (!isset($course_images)) return '';

        $elemens = $course_images->map(function ($v) {
            if (isset($v->image_order)) {
                return $v->hospital_image;
            }
        });
        return $elemens ?? null;
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
