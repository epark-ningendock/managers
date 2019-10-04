<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Collection;

use App\Reservation;
use App\Holiday;
use App\ReservationOption;
use App\ReservationAnswer;
use App\Course;
use App\Customer;

use App\Mail\ReservationCompleteMail;
use App\Mail\ReservationCancelMail;

use App\Exceptions\ReservationUpdateException;
use App\Exceptions\ReservationDateException;
use App\Exceptions\ReservationFrameException;

use Carbon\Carbon;
use \GuzzleHttp\Client;
use Log;

// epark本部宛てアドレス
define('EPARK_MAIL_TO', Config::get('app.epark_mail_to'));
class ReservationService
{
    // 処理区分[登録]
    const REGISTRATION = 0;

    /**
     * 予約API 対象取得
     *
     * @param  $reservation_id
     * @return App\Reservation
     */
    public function find($reservation_id)
    {
        $entity = Reservation::with([
            'hospital',
            'hospital.district_code',
            'hospital.district_code.prefecture',
            'hospital.reception_email_setting',
            'course',
            'reservation_answers',
            'reservation_options',
            'reservation_options.option',
            'customer',
            'customer.prefecture',
        ])->find($reservation_id);
        return $entity;
    }

    /**
     * cancel(変更)可能かどうか
     *
     * @param  App\Reservation $entity
     * @return 0:変更可 1:変更不可
     */
    public function isCancel($entity)
    {
        if (!isset($entity)) {
            Log::error('変更不可:' . '予約データなし');
            return 1;
        }

        // 予約日
        $reservation_date = $entity->reservation_date;

        // キャンセル受付変更期限（日）
        $cancellation_deadline = intval($entity->course->cancellation_deadline);

        // キャンセル可能日
        $cancellation_date = Carbon::create($reservation_date)->addDay($cancellation_deadline);

        // キャンセル要求日
        $today = Carbon::now();

        if ($cancellation_date < $today) {
            Log::error('変更不可 予約ID:' . $entity->id);
            Log::error('変更可能期間:' . $reservation_date . '->' . date('Y-m-d', strtotime($cancellation_date)));
            return 1;
        }
        return 0; // 変更可
    }

    /**
     * 予約API 共通API call
     *
     * @param  Illuminate\Http\Request  $request
     * @return response json
     */
    public function request($request)
    {
        // TODO: 共通API call request param/response 不明
        return $request;
    }

    /**
     * 予約API ステータス更新
     *
     * @param  App\Reservation $entity
     * @return \Illuminate\Http\Response
     */
    public function update($entity)
    {
        DB::beginTransaction();
        try {
            Reservation::where('id', $entity->id)->update(['reservation_status' => $entity->reservation_status]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e);
            throw new ReservationUpdateException();
        }
    }

    /**
     * 予約可能かどうか
     *
     * @param  Illuminate\Http\Request  $request
     * @return App\Reservation
     */
    public function isReservation($request)
    {
        // パラメータ取得
        $process = intval($request->input('process_kbn'));
        $hospital_id = $request->input('hospital_id');
        $course_id = $request->input('course_id');
        $reservation_date = intval(date('Ymd', strtotime($request->input('reservation_date'))));

        // 新規の場合、休診日かどうか確認
        if ($process === self::REGISTRATION) {
            $Holidays = Holiday::where('hospital_id', $hospital_id)->where('date', $reservation_date)->get();
            if (!$Holidays->isEmpty()) {
                Log::error('休診日:' . $request->input('reservation_date'));
                Log::error('医療機関ID:' . $request->input('hospital_id'));
                throw new ReservationUpdateException();
            }
        }

        // 更新の場合、予約情報があるかどうか確認
        if ($process !== self::REGISTRATION) {
            $reservation = Reservation::getUpdateTarget($request, $reservation_date);
            if ($reservation === null) {
                Log::error('変更不可:' . $request->input('reservation_date'));
                Log::error('医療機関ID:' . $request->input('hospital_id'));
                Log::error('コースID:' . $request->input('course_id'));
                Log::error('顧客email:' . $request->input('email'));
                throw new ReservationUpdateException();
            }
        }

        // 対象取得
        $entity = Course::with([
            'calendar_days' => function ($query) use ($reservation_date) {
                $query->where('date', $reservation_date);
            }
        ])->find($course_id);

        // 予約情報チェック
        // 受付許可日／受付終了日確認
        if (
            $entity->reception_start_date > $reservation_date
            && $entity->reception_end_date < $reservation_date
        ) {
            Log::error('予約不可:' . $reservation_date);
            Log::error('受付許可日:' . $entity->reception_acceptance_date);
            Log::error('受付終了日:' . $entity->reception_end_date);
            throw new ReservationDateException();
        }

        if ($process === self::REGISTRATION) { // 新規の場合、予約枠数の確認
            // 既予約数取得
            $reservation_count = Reservation::getReservationCount($request, $reservation_date);
            // 予約受付、枠数確認
            $dummy = $entity->calendar_days->map(function ($c) use ($reservation_count) {
                $reservation_frames = intval($c->reservation_frames);
                $is_reservation_acceptance = intval($c->is_reservation_acceptance);
                if ($is_reservation_acceptance === 0 || $reservation_frames === 0) { // 枠なし
                    Log::error('予約枠数なし:' . $c->reservation_frames);
                    throw new ReservationFrameException();
                }
                if ($reservation_frames <= $reservation_count) { // 空きなし
                    Log::error('予約枠数:' . $reservation_frames);
                    Log::error('既予約数:' . $reservation_count);
                    throw new ReservationFrameException();
                }
                return $c;
            });
        }
        return true;
    }

    /**
     * 予約API登録／更新
     *
     * @param  Illuminate\Http\Request  $request
     * @param  array $epark
     * @return App\Reservation
     */
    public function store($request, $epark)
    {
        // 更新
        DB::beginTransaction();
        try {
            // 顧客entity生成
            $customer = $this->_customer_from_request($request);

            // 予約情報entity生成
            $reservation = $this->_reservation_from_request($request);

            // 顧客登録/更新
            // $customer = $customer->saveOrFail();
            $customer->saveOrFail();
            // 予約登録/更新
            $reservation->customer_id = $customer->id;
            $reservation->saveOrFail();

            // 予約オプション洗い替え
            ReservationOption::where('reservation_id', $reservation->id)->delete();

            // 予約オプションentity生成/登録
            $reservation_options = $this->_reservation_options_from_request($request, $reservation->id);
            foreach ($reservation_options as $ro) {
                $ro->saveOrFail();
            }

            // 予約回答洗い替え
            ReservationAnswer::where('reservation_id', $reservation->id)->delete();

            // 予約回答entity生成/登録
            $reservation_answers = $this->_reservation_answers_from_request($request, $reservation->id);
            foreach ($reservation_answers as $ra) {
                $ra->saveOrFail();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e);
            throw $e;
        }
        return $reservation;
    }

    /**
     * 予約API登録／更新／キャンセルメール送信
     *
     * @param  App\Reservation $entity
     * @return 0:成功 1:失敗
     */
    public function mail($reservation)
    {
        // メールで使用する情報の取得
        $entity = $this->find($reservation->id);

        // 処理区分追加
        $entity->process_kbn = $reservation->process_kbn ?? -1;

        // キャンセル処理(処理区分がある)かどうか
        $is_cancel = $entity->process_kbn === -1 ? true : false;

        // メール送信フラグ追加
        $entity->mail_fg = $reservation->mail_fg ?? 1;

        // 受付メール設定取得
        $to = EPARK_MAIL_TO;
        $mails = [
            $entity->hospital->reception_email_setting->reception_email1,
            $entity->hospital->reception_email_setting->reception_email2,
            $entity->hospital->reception_email_setting->reception_email3,
            $entity->hospital->reception_email_setting->reception_email4,
            $entity->hospital->reception_email_setting->reception_email5,
        ];
        if ($entity->hospital->reception_email_setting->web_reception_email_flg === 0) {
            foreach ($mails as $m) {
                if (isset($m)) {
                    $to .= ',' . $m;
                }
            }
        }
        // メール送信フラグ
        $mail_fg = $is_cancel ? $reservation->mail_fg : 1;
        if ($mail_fg === 1) {
            $to .= ',' . $entity->customer->email;
        }

        // email address 配列へ
        $tos = explode(',', $to);
        try {
            if (!$is_cancel) { // 登録/変更完了メール
                Mail::to($tos)->send(new ReservationCompleteMail($entity));
            } else { // 予約キャンセルメール
                Mail::to($tos)->send(new ReservationCancelMail($entity));
            }
        } catch (Exception $e) { // mail送信失敗
            Log::error($e);
            return -1; // メール送信失敗
        }
        return 1; // 予約/変更/キャンセル成功
    }

    /**
     * 顧客 entity生成
     *
     * @param  \Illuminate\Http\Request  $request
     * @return App\Customer entity
     */
    private function _customer_from_request($request): Customer
    {
        // 受診者配列先頭取得
        $members = $request->input('regist_member');
        $member = collect(json_decode(json_encode($members)))->filter(function ($m) {
            return isset($m);
        })->toArray()[0];

        // 処理区分セット
        $process = intval($request->input('process_kbn'));
        if ($process === self::REGISTRATION) { // 新規
            $entity = new Customer();
            $entity->id = 0;
        } else { // 更新
            $entity = Customer::find($request->input('customer_id'));
        }
        $entity->parent_customer_id = null;
        // 設定不要 ※テーブルからunique制約外す
        $entity->member_number = 0;

        // 新規登録時は顧客テーブルへ受診者情報を新規顧客として登録する。
        $entity->registration_card_number = $request->input('registration_card_num') ?? $entity->registration_card_number;
        $entity->family_name = $member->last_name;
        $entity->first_name = $member->first_name;
        $entity->family_name_kana = $member->last_name_kana ?? $entity->family_name_kana;
        $entity->first_name_kana = $member->first_name_kana ?? $entity->first_name_kana;
        $entity->tel = $request->input('tel_no') ?? $entity->tel;
        $entity->email = $request->input('email');
        $entity->sex = $member->sex ?? $entity->sex;
        $entity->birthday = date('Ymd', strtotime($member->birthday)) ?? $entity->birthday;

        // 以下補完（epark人間ドックの場合、予約者=受診者になる)
        $district =  $request->input('district') ?? '';
        $building_name =  $request->input('building_name') ?? '';
        $address2 = $district . ' ' . $building_name;
        $entity->postcode = $request->input('post_code') ?? $entity->postcode;
        $entity->prefecture_id = $request->input('prov_code') ?? $entity->prefecture_id;
        $entity->address1 = $request->input('city') ?? '';
        $entity->address2 = $address2 !== ' ' ? $address2 : $entity->address2;
        $entity->claim_count = $process === self::REGISTRATION ? 0 : $entity->claim_count;
        $entity->recall_count = $process === self::REGISTRATION ? 0 : $entity->recall_count;
        $entity->epark_member_id = $request->input('epark_member_id') ?? $entity->epark_member_id;

        return $entity;
    }

    /**
     * 予約 entity生成
     *
     * @param  \Illuminate\Http\Request  $request
     * @return App\Reservation entity
     */
    private static function _reservation_from_request($request): Reservation
    {
        // コース情報取得
        $course = Course::with([
            'course_options',
            'course_options.option',
            'tax_class',
        ])
            ->find($request->input('course_id'));

        // 受診者配列先頭取得
        $members = $request->input('regist_member');
        $member = collect(json_decode(json_encode($members)))->filter(function ($m) {
            return isset($m);
        })->toArray()[0];

        // 処理区分セット
        $process = intval($request->input('process_kbn'));
        if ($process === self::REGISTRATION) { // 新規
            $entity = new Reservation();
            $entity->id = 0;
        } else { // 更新
            $entity = Reservation::find($request->input('reservation_id'));
        }

        $entity->hospital_id = $request->input('hospital_id');
        $entity->course_id = $request->input('course_id');
        $entity->reservation_date = $request->input('reservation_date');
        $entity->start_time_hour = $request->input('start_time_hour') ?? $entity->start_time_hour;
        $entity->start_time_min = $request->input('start_time_min') ?? $entity->start_time_min;
        $entity->end_time_hour = $request->input('end_time_hour') ?? $entity->end_time_hour;
        $entity->end_time_min = $request->input('end_time_min') ?? $entity->end_time_min;
        $entity->channel = 1;
        $entity->reservation_status = $process === self::REGISTRATION ? 1 : $entity->reservation_status;
        $entity->user_message = $request->input('user_message') ?? $entity->user_message;
        $entity->site_code = $request->input('site_code') ?? $entity->site_code;
        $entity->customer_id = $request->input('customer_id') ?? $entity->customer_id;
        $entity->epark_member_id = $request->input('epark_member_id') ?? $entity->epark_member_id;
        // 設定必要なし 会員番号
        $entity->member_number = 0;
        $entity->terminal_type = $request->input('terminal_tp');
        $entity->time_selected = $request->input('time_selected') ?? $entity->time_selected;

        $entity->is_repeat = $member->repeat_fg ?? $entity->is_repeat;
        $entity->is_representative = $member->representative_fg;

        // TODO: timezone_pattern_id/timezone_id/orderは「空満情報取得API」から取得する必要あり
        // define('GET_VACANCY_URI', 'https://d-api.xaas.jp/appointManage/v1/hybappoint/getavailabilitydock');
        $entity->timezone_pattern_id = 0;
        $entity->timezone_id = 0;
        $entity->order = $entity->timezone_pattern_id . '_' . $entity->timezone_id . '_' . '0';
        $entity->tax_included_price = $request->input('course_price_tax') ?? $entity->tax_included_price;

        // 設定必要なし 調整価格
        $entity->adjustment_price = 0;
        $entity->tax_rate = $course->tax_class->rate ?? 0;

        // option配列の先頭取得
        $options = $request->input('option_array');
        $option = collect(json_decode(json_encode($options)))->filter(function ($o) {
            return isset($o->option_cd);
        })->toArray()[0] ?? [];

        $entity->second_date = $option->other_info->second_date ?? $entity->second_date;
        $entity->third_date = $option->other_info->third_date ?? $entity->third_date;
        $entity->is_choose = $option->other_info->choose_fg ?? $entity->is_choose;
        $entity->campaign_code = $option->other_info->campaign_cd ?? $entity->campaign_code;
        $entity->tel_timezone = $option->other_info->tel_timezone ?? $entity->tel_timezone;
        $entity->insurance_assoc_id = $option->other_info->insurer_number ?? $entity->insurance_assoc_id;
        $entity->insurance_assoc = $option->other_info->insurance_assoc ?? $entity->insurance_assoc;

        $entity->mail_type = $process === self::REGISTRATION ? '1' : '2';
        $entity->cancelled_appoint_code = $request->input('cancelled_appoint_code') ?? $entity->cancelled_appoint_code;

        $entity->is_billable = $process === self::REGISTRATION ? 0 : $entity->is_billable;
        $entity->claim_month = $process === self::REGISTRATION ? '0' : $entity->claim_month;

        $entity->is_payment = $request->input('payment_flg') ?? $entity->is_payment;
        $entity->payment_status = $request->input('payment_status') ?? $entity->payment_status;
        $entity->trade_id = $request->input('trade_id') ?? $entity->trade_id;
        $entity->order_id = $request->input('order_id') ?? $entity->order_id;

        $entity->settlement_price = $request->input('card_payment_amount') ?? $entity->settlement_price;
        $entity->payment_method = $request->input('payment_method') ?? $entity->payment_method;
        $entity->cashpo_used_price = $request->input('cashpo_used_amount') ?? $entity->cashpo_used_price;
        $entity->amount_unsettled = $request->input('amount_unsettled') ?? $entity->amount_unsettled;
        $entity->reservation_memo = $request->input('user_message') ?? $entity->reservation_memo;
        $entity->todays_memo = $entity->todays_memo;
        $entity->internal_memo = $entity->internal_memo;
        // 設定必要なし 受付番号
        // $entity->acceptance_number = $process === self::REGISTRATION ? 0 : $entity->acceptance_number;

        $entity->y_uid = $request->input('y_uid') ?? $entity->y_uid;

        return $entity;
    }

    /**
     * 予約オプション entity生成
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $reservation_id
     * @return Illuminate\Support\Collection
     */
    private function _reservation_options_from_request($request, $reservation_id): Collection
    {
        // request options取得
        $options = collect(json_decode(json_encode($request->input('option_array'))))->filter(function ($o) {
            return isset($o->option_cd);
        });

        $results = $options->map(function ($o) use ($reservation_id) {
            $entity = new ReservationOption();
            $entity->reservation_id = $reservation_id;
            $entity->option_id = $o->option_cd;
            $entity->option_price = $o->option_price_tax ?? 0;
            return $entity;
        });

        return $results;
    }

    /**
     * 予約回答 entity生成
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $reservation_id
     * @return　Illuminate\Support\Collection
     */
    private function _reservation_answers_from_request($request, $reservation_id): Collection
    {
        // request q_anser取得
        $q_answers = collect(json_decode(json_encode($request->input('q_anser'))))->filter(function ($q) {
            return isset($q->question_title);
        });

        // course情報取得
        $reservation = Reservation::with([
            'course',
            'course.course_questions',
        ])->find($reservation_id);
        $course_id = $reservation->course->id ?? 0;
        $course_questions = $reservation->course->course_questions->toArray() ?? [];

        $idx = 0;
        foreach ($q_answers as $qa) {
            $qa->reservation_id = $reservation_id;
            $qa->course_id = $course_id;
            // TODO: https://docknet.backlog.jp/view/APIENGINEDEV-36
            $qa->course_question = $course_questions[$idx++] ?? null;
        }

        $results = collect(json_decode(json_encode($q_answers)))->map(function ($qa) {

            foreach($qa->answers as $answer) {
                $entity = new ReservationAnswer();
                $entity->reservation_id = $qa->reservation_id;
                $entity->course_id = $qa->course_id;
                $entity->course_question_id = $qa->course_question->id ?? 0;
                $entity->question_title = $qa->question_title ?? '';

                $arr = explode('|', $answer->answer);
                $entity->question_answer01 = $arr[0] ?? $entity->question_answer01;
                $entity->answer01 = $arr[1] ?? intval($entity->answer01);
                $entity->question_answer02 = $arr[2] ?? $entity->question_answer02;
                $entity->answer02 = $arr[3] ?? intval($entity->answer02);
                $entity->question_answer03 = $arr[4] ?? $entity->question_answer03;
                $entity->answer03 = $arr[5] ?? intval($entity->answer03);
                $entity->question_answer04 = $arr[6] ?? $entity->question_answer04;
                $entity->answer04 = $arr[7] ?? intval($entity->answer04);
                $entity->question_answer05 = $arr[8] ?? $entity->question_answer05;
                $entity->answer05 = $arr[9] ?? intval($entity->answer05);
                $entity->question_answer06 = $arr[10] ?? $entity->question_answer06;
                $entity->answer06 = $arr[11] ?? intval($entity->answer06);
                $entity->question_answer07 = $arr[12] ?? $entity->question_answer07;
                $entity->answer07 = $arr[13] ?? intval($entity->answer07);
                $entity->question_answer08 = $arr[14] ?? $entity->question_answer08;
                $entity->answer08 = $arr[15] ?? intval($entity->answer08);
                $entity->question_answer09 = $arr[16] ?? $entity->question_answer09;
                $entity->answer09 = $arr[17] ?? intval($entity->answer09);
                $entity->question_answer10 = $arr[18] ?? $entity->question_answer10;
                $entity->answer10 = $arr[19] ?? intval($entity->answer10);

                return $entity;
            }

        });
        return $results;
    }
}