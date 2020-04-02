<?php

namespace App\Services;

use App\Calendar;
use App\CalendarDay;
use App\ContractInformation;
use App\ContractPlan;
use App\CourseQuestion;
use App\Enums\Status;
use App\Hospital;
use App\HospitalCategory;
use App\HospitalEmailSetting;
use App\HospitalOptionPlan;
use App\HospitalPlan;
use App\MonthlyWaku;
use App\ReservationKenshinSysOption;
use App\TaxClass;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Collection;

use App\Enums\HplinkContractType;
use App\Enums\IsFreeHpLink;
use App\Enums\ReservationStatus;
use App\Reservation;
use App\Holiday;
use App\HospitalPlans;
use App\ReservationOption;
use App\ReservationAnswer;
use App\Course;
use App\Customer;

use App\Mail\ReservationCompleteMail;
use App\Mail\ReservationCancelMail;

use App\Exceptions\ReservationUpdateException;
use App\Exceptions\ReservationDateException;
use App\Exceptions\ReservationFrameException;

use GuzzleHttp\Client;
use Carbon\Carbon;
use Log;

// epark本部宛てアドレス
define('EPARK_MAIL_TO', config('mail.to.gyoumu'));
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
            'hospital.contract_information',
            'hospital.district_code',
            'hospital.district_code.prefecture',
            'hospital.hospital_email_setting',
            'course',
            'course.course_images',
            'reservation_answers',
            'reservation_options',
            'reservation_options.option',
            'reservation_kenshin_sys_options',
            'customer',
            'customer.prefecture',
        ])->find($reservation_id);
        return $entity;
    }

    /**
     * @param $epark_member_id
     */
    public function find_all_id($epark_member_id) {

        return Reservation::where('epark_member_id', $epark_member_id)
            ->where('reservation_status', '<>', ReservationStatus::CANCELLED)
            ->orderBy('reservation_date', 'DESC')
            ->limit(20)
            ->get();

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

        // 受診日
        $completed_date = $entity->reservation_date;

        // キャンセル受付変更期限（日）
        $cancellation_deadline = intval($entity->course->cancellation_deadline);

        // キャンセル可能日
        $cancellation_date = Carbon::parse($completed_date)->subDay($cancellation_deadline);

        // キャンセル要求日
        $today = Carbon::today();

        if ($cancellation_date < $today) {
            Log::error('変更不可 予約ID:' . $entity->id);
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
    public function request($request, $entity)
    {
        $uri = env('RESERVATION_HISTORY_API').'set/';

        $headers = $this->getRequestHeaders();

        $params = $this->getApiParams($request, $entity);

        $quotaguard_env = env("QUOTAGUARDSTATIC_URL");
        $quotaguard = parse_url($quotaguard_env);

        $proxyUrl       = $quotaguard['host'].":".$quotaguard['port'];
        $proxyAuth       = $quotaguard['user'].":".$quotaguard['pass'];

        $client = app()->make(Client::class);
        try {
            Log::info('予約履歴 APIリクエスト処理', ['予約ID' => $entity->id ]);
            $response = $client->request('POST', $uri, [
                'headers' => $headers,
                'query' => $params,
                'proxy' => $quotaguard_env,
//                'proxyauth' => 'CURLAUTH_BASIC',
//                'proxyuserpwd' => $proxyAuth,
            ]);
        } catch (Exception $e) {
            Log::error('予約履歴 APIリクエスト処理 システムエラー', ['message' => $e->getMessage()]);
        }
    }

    /**
     * APIのリクエストヘッダーを作成する
     *
     * @return array
     */
    public function getRequestHeaders()
    {
        $headers = [
            // コンテンツタイプ 固定値「application/json; charset=utf-8」
            'Content-Type' => 'application/json; charset=utf-8',
        ];

        return $headers;
    }

    public function getApiParams($request, $entity) {
        $course = Course::find($request->input('course_id'));
        $course_name = '';
        if ($course) {
            $course_name = $course->name;
        }
        $contract_information = ContractInformation::where('hospital_id', $entity->hospital_id)->first();
        $hospital_category = HospitalCategory::where('image_order', 1)
            ->where('file_location_no', 1)
            ->where('hospital_id', $entity->hospital_id)
            ->first();

        $params = [
            // EPARK会員ID
            'member_id' => $request->input('epark_member_id'),
            // サービスID
            'service_id' => \config('constant.service_id'),
            // 予約ID
            'appoint_id' => $entity->id,
            // 予約名（コース名）
            'appoint_name' => $course_name,
            // 店舗ID
            'shop_id' => $entity->hospital_id,
            // 店舗名
            'shop_name' => $course->hospital->name,
            // 店舗URL
            'shop_url' => $this->createURL().'/'.$contract_information->code . '/basic.html',
            // 店舗画像URL
            'shop_image_url' => $hospital_category->hospital_image->path,
            // 予約キャンセルURL
            'appoint_cancel_url' => $this->createURL().'/'.'reservation/confirm.html?code=&id=' . $entity->id . '&sid=' . $entity->hospital_id,
            // 予約変更URL
            'appoint_edit_url' => $this->createURL().'/'.'reservation/confirm.html?code=&id=' . $entity->id . '&sid=' . $entity->hospital_id,
            // 予約確認URL
            'appoint_confirm_url' => $this->createURL().'/'.'reservation/confirm.html?code=&id=' . $entity->id . '&sid=' . $entity->hospital_id,
            // 予約開始日時
            'appoint_start_date' => Carbon::parse($entity->reservation_date)->format('Y-m-d H:i'),
            // 予約終了日時
            'appoint_end_date' => Carbon::parse($entity->reservation_date)->format('Y-m-d H:i'),
            // 予約ステータス
            'appoint_status' => $this->changeReservationStatus($entity),
        ];

        return $params;
    }

    /**
     * @return string
     */
    private function createURL() {
        return (empty($_SERVER['HTTPS']) ? 'http://' : 'https://').$_SERVER['HTTP_HOST'];
    }

    /**
     * @param $entity
     * @return int
     */
    private function changeReservationStatus($entity) {
        if ($entity->reservation_status == ReservationStatus::CANCELLED) {
            return 9;
        }

        if ($entity->reservation_status == ReservationStatus::PENDING) {
            return 0;
        }
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
     * @param $entity
     * @param $count
     */
    public function registReservationToCalendar($entity, $count) {

        $target = Carbon::parse($entity->reservation_date);
        $calendar_day = CalendarDay::where('calendar_id', $entity->course->calendar_id)
            ->whereDate('date', $target->toDateString())
            ->first();

        $reservation_count = intval($calendar_day->reservation_count) + $count;
        if ($reservation_count < 0) {
            $reservation_count = 0;
        }
        $calendar_day->reservation_count = $reservation_count;
        $calendar_day->save();

    }

    /**
     * 予約可能かどうか
     *
     * @param  Illuminate\Http\Request  $request
     * @return App\Reservation
     */
    public function isReservation($request, $reservation_id)
    {
        // パラメータ取得
        $process = intval($request->input('process_kbn'));
        $course_id = $request->input('course_id');
        $reservation_date = date('Y-m-d', strtotime($request->input('reservation_date')));

        $course = Course::find($course_id);
        if (!$course) {
            return 3;
        }
        $calendar_day = CalendarDay::where('date', $reservation_date)
            ->where('calendar_id', $course->calendar_id)
            ->first();

        if (!$calendar_day) {
            return 3;
        }

        // 新規の場合、休診日かどうか確認
        if ($process === self::REGISTRATION && $calendar_day->is_holiday == 1) {
            return 1;
        }

        // 更新の場合、予約情報があるかどうか確認
        if ($process !== self::REGISTRATION) {
            $reservation = Reservation::find($reservation_id);
            if (!$reservation) {
                return 2;
            }
        }

        // 予約情報チェック
        // 受付許可日／受付終了日確認
        $start_month = $course->reception_start_date / 1000;
        $start_day = $course->reception_start_date % 1000;
        $reception_start_date = Carbon::today();
        $reception_start_date->addMonthsNoOverflow($start_month);
        $reception_start_date->addDays($start_day);
        $end_month = $course->reception_end_date / 1000;
        $end_day = $course->reception_end_date % 1000;
        $reception_end_date = Carbon::today();
        $reception_end_date->addMonthsNoOverflow($end_month);
        $reception_end_date->addDays($end_day);
        if ($reception_start_date > $reservation_date
            || $reception_end_date < $reservation_date
        ) {
            return 3;
        }

        if ($process === self::REGISTRATION) { // 新規の場合、予約枠数の確認
            if ($calendar_day->reservation_frames == 0 || $calendar_day->is_reservation_acceptance != 0) {
                return 4;
            }

            if ($calendar_day->reservation_frames <= $calendar_day->reservation_count) {
                return 5;
            }
        }
        return 0;
    }

    /**
     * 予約API登録／更新
     *
     * @param  Illuminate\Http\Request  $request
     * @param  array $epark
     * @return App\Reservation
     */
    public function store($request)
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
            $customer->save();
            // 予約登録/更新
            $reservation->customer_id = $customer->id;
            $reservation->save();

            // 予約オプション洗い替え
            ReservationOption::where('reservation_id', $reservation->id)->delete();

            // 予約オプションentity生成/登録
            $reservation_options = $this->_reservation_options_from_request($request, $reservation->id);
            foreach ($reservation_options as $ro) {
                $ro->saveOrFail();
            }

            $reservation_kenshin_sys_options = $this->_reservation_kenshin_sys_options_from_request($request, $reservation->id);
            foreach ($reservation_kenshin_sys_options as $ro) {
                $ro->saveOrFail();
            }

            // 予約回答洗い替え
            ReservationAnswer::where('reservation_id', $reservation->id)->delete();

            // 予約回答entity生成/登録
            $this->_reservation_answers_from_request($request, $reservation->id);

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
        $hospital_email_setting = HospitalEmailSetting::where('hospital_id', $entity->hospital_id)->first();

        \Illuminate\Support\Facades\Log::info('予約ID:'. $reservation->id);
        \Illuminate\Support\Facades\Log::info('医療機関ID:'. $reservation->hospital_id);

        // キャンセル処理(処理区分がある)かどうか
        $is_cancel = $entity->reservation_status === ReservationStatus::CANCELLED ? true : false;

        // メール送信フラグ追加
        $entity->mail_fg = $reservation->mail_fg ?? 1;
        // 顧客へメール送信
        if ($reservation->mail_fg == 1) {
            $to = $entity->customer->email;
            try {
                if (!$is_cancel) { // 登録/変更完了メール
                    Mail::to($to)->send(new ReservationCompleteMail($entity, true));
                } else { // 予約キャンセルメール
                    Mail::to($to)->send(new ReservationCancelMail($entity, true));
                }
            } catch (\Exception $e) { // mail送信失敗
                Log::error($e);
                return -1; // メール送信失敗
            }
        }

        $hospital_mails = [];
        if ($hospital_email_setting) {
            $hospital_mails = [
                $hospital_email_setting->reception_email1,
                $hospital_email_setting->reception_email2,
                $hospital_email_setting->reception_email3,
                $hospital_email_setting->reception_email4,
                $hospital_email_setting->reception_email5,
            ];
        }

        foreach ($hospital_mails as $m) {
            if (!empty($m)) {
                $tos[] = $m;
            }
        }

        try {
            // 医療機関へメール送信
            if ($is_cancel && $hospital_email_setting->in_hospital_cancellation_email_reception_flg == 1) {
                Mail::to($tos)->send(new ReservationCancelMail($entity, false));
            } elseif ($hospital_email_setting->web_reception_email_flg == 1) {
                Mail::to($to)->send(new ReservationCompleteMail($entity, false));
            }

            // 事業部へメール
            if ($is_cancel && $hospital_email_setting->in_hospital_cancellation_email_reception_flg == 1) {
                Mail::to(EPARK_MAIL_TO)->send(new ReservationCancelMail($entity, false));
            } elseif ($hospital_email_setting->web_reception_email_flg == 1) {
                Mail::to(EPARK_MAIL_TO)->send(new ReservationCompleteMail($entity, false));
            }
        } catch (\Exception $e) { // mail送信失敗
            Log::error($e);
        }


        return 1; // 予約/変更/キャンセル成功
    }

    /**
     * 顧客 entity生成
     *
     * @param  \Illuminate\Http\Request  $request
     * @return App\Customer entity
     */
    private function _customer_from_request($request)
    {

        $entity = Customer::where('epark_member_id', $request->input('epark_member_id'))
                            ->where('hospital_id', $request->input('hospital_id'))
                            ->first();

        // 処理区分セット
        $process = intval($request->input('process_kbn'));
        if (! $entity) { // 新規
            $entity = new Customer();
            $entity->hospital_id = $request->input('hospital_id');
        }
        $entity->parent_customer_id = 0;
        // 設定不要 ※テーブルからunique制約外す
        $entity->member_number = 0;

        // 新規登録時は顧客テーブルへ受診者情報を新規顧客として登録する。
        $entity->registration_card_number = $request->input('registration_card_num') ?? $entity->registration_card_number ?? '';
        $entity->family_name = $request->input('last_name');
        $entity->first_name = $request->input('first_name');
        $entity->family_name_kana = $request->input('last_name_kana') ?? $entity->family_name_kana ?? '';
        $entity->first_name_kana = $request->input('first_name_kana') ?? $entity->first_name_kana ?? '';
        $entity->tel = $request->input('tel_no') ?? $entity->tel ?? '';
        $entity->email = $request->input('email');
        $entity->sex = $request->input('sex') ?? $entity->sex ?? '';
        $entity->birthday = date('Ymd', strtotime($request->input('birthday'))) ?? $entity->birthday ?? '';

        // 以下補完（epark人間ドックの場合、予約者=受診者になる)
        // $district =  $request->input('district') ?? '';
        // $building_name =  $request->input('building_name') ?? '';
        // $address2 = $district . ' ' . $building_name;
        $entity->postcode = $request->input('post_code') ?? $entity->postcode;
        $entity->prefecture_id = $request->input('prov_code') ?? $entity->prefecture_id;
        $entity->address1 = $request->input('district') ?? $entity->address1;
        $entity->address2 = $request->input('building_name') ?? $entity->address2;
        $entity->claim_count = $process === self::REGISTRATION ? 0 : $entity->claim_count;
        $entity->recall_count = $process === self::REGISTRATION ? 0 : $entity->recall_count;
        $entity->epark_member_id = $request->input('epark_member_id') ?? $entity->epark_member_id;
        $entity->status = Status::VALID;


        return $entity;
    }

    /**
     * 予約 entity生成
     *
     * @param  \Illuminate\Http\Request  $request
     * @return App\Reservation entity
     */
    private function _reservation_from_request($request): Reservation
    {
        // コース情報取得
        $course = Course::with([
            'course_options',
            'course_options.option',
            'tax_class',
        ])
            ->find($request->input('course_id'));

        $hospital = Hospital::find($request->input('hospital_id'));

        // 処理区分セット
        $process = intval($request->input('process_kbn'));
        if ($process === self::REGISTRATION) { // 新規
            $entity = new Reservation();
            // 医療機関プラン取得
            $hospitalPlan = $this->getApplyPlan($request->input('hospital_id'));
            $contract_plan = ContractPlan::find($hospitalPlan->contract_plan_id);
            $entity->fee_rate = $contract_plan->fee_rate;
        } else { // 更新
            $entity = Reservation::find($request->input('reservation_id'));
        }

        $entity->hospital_id = $request->input('hospital_id');
        $entity->course_id = $request->input('course_id');
        $entity->course_name = $request->input('cours_name');
        $entity->reservation_date = new Carbon($request->input('reservation_date'));
        $entity->start_time_hour = $request->input('start_time_hour') ?? $entity->start_time_hour;
        $entity->start_time_min = $request->input('start_time_min') ?? $entity->start_time_min;
        $entity->end_time_hour = $request->input('end_time_hour') ?? $entity->end_time_hour;
        $entity->end_time_min = $request->input('end_time_min') ?? $entity->end_time_min;
        $entity->channel = 1;
        $entity->reservation_status = $process === self::REGISTRATION ? ReservationStatus::PENDING : $entity->reservation_status;
        $entity->user_message = $request->input('user_message');
        $entity->reservation_memo = $request->input('user_message');
        $entity->site_code = $request->input('site_code') ?? $entity->site_code;
        $entity->customer_id = $request->input('customer_id') ?? $entity->customer_id;
        $entity->epark_member_id = $request->input('epark_member_id') ?? $entity->epark_member_id;
        $entity->terminal_type = $request->input('terminal_tp');
        $entity->time_selected = $request->input('time_selected') ?? $entity->time_selected;

        $entity->is_repeat = 0;
        $entity->is_representative = $request->input('representative_fg');

        $entity->tax_included_price = $request->input('course_price_tax') ?? $entity->tax_included_price;
        $entity->tax_rate = 10;
//        $entity->tax_rate = resolve(TaxClass::class)->nowTax();

        $other_info = $request->input('other_info');
        if (!empty($other_info)) {
            $entity->second_date = $other_info['second_date'] ?? $entity->second_date;
            $entity->third_date = $other_info['third_date'] ?? $entity->third_date;
            $entity->is_choose = $other_info['choose_fg'] ?? $entity->is_choose;
            $entity->campaign_code = $other_info['campaign_cd'] ?? $entity->campaign_code;
            $entity->tel_timezone = $other_info['tel_timezone'] ?? $entity->tel_timezone;
            $entity->insurance_assoc_id = $other_info['insurer_number'] ?? $entity->insurance_assoc_id;
            $entity->insurance_assoc = $other_info['insurance_assoc'] ?? $entity->insurance_assoc;
        }

        $entity->mail_type = $process === self::REGISTRATION ? '1' : '2';
        $entity->cancelled_appoint_code = $request->input('cancelled_appoint_code') ?? $entity->cancelled_appoint_code;

        $entity->is_billable = $process === self::REGISTRATION ? 0 : $entity->is_billable;
        $entity->claim_month = $this->getClaimMonth($entity->reservation_date);
        $entity->is_payment = $request->input('payment_flg') ?? $entity->is_payment;
        $entity->payment_status = $request->input('payment_status') ?? $entity->payment_status;
        $entity->trade_id = $request->input('trade_id') ?? $entity->trade_id;
        $entity->order_id = $request->input('order_id') ?? $entity->order_id;
        $entity->settlement_price = $request->input('card_payment_amount') ?? $entity->settlement_price;
        $entity->payment_method = $request->input('payment_method') ?? $entity->payment_method;
        $entity->cashpo_used_price = $request->input('cashpo_used_amount') ?? $entity->cashpo_used_price;
        $entity->amount_unsettled = $request->input('amount_unsettled') ?? $entity->amount_unsettled;
        $entity->y_uid = $request->input('y_uid') ?? $entity->y_uid;
        $entity->applicant_name = $request->input('last_name') . $request->input('first_name');
        $entity->applicant_name_kana = $request->input('last_name_kana') . $request->input('first_name_kana');
        $entity->applicant_tel = $request->input('tel_no') ?? $entity->applicant_tel;
        $entity->medical_examination_system_id = $hospital->medical_examination_system_id;
        $entity->kenshin_sys_hospital_id = $hospital->kenshin_sys_hospital_id;
        $entity->kenshin_sys_yoyaku_no = $request->input('kenshin_sys_yoyaku_no') ?? null;
        $entity->kenshin_sys_start_time = $request->input('kenshin_sys_start_time') ?? '';
        $entity->kenshin_sys_end_time = $request->input('kenshin_sys_end_time') ?? '';
        $entity->kenshin_sys_yoyaku_waku_no = $request->input('kenshin_sys_yoyaku_waku_no') ?? null;
        $entity->kenshin_sys_yoyaku_waku_seq = $request->input('kenshin_sys_yoyaku_waku_seq') ?? null;
        $entity->kenshin_sys_yoyaku_waku_nm = $request->input('kenshin_sys_yoyaku_waku_nm') ?? null;
        $entity->kenshin_sys_course_id = $request->input('kenshin_sys_course_id') ?? null;

        $options = [];
        if (!empty($request->input('option_array'))) {
            $options = json_decode(json_encode($request->input('option_array')));
        }

        if ($hospital->hplink_contract_type == HplinkContractType::NONE) {
            $option_price = 0;

            foreach ($options as $option) {
                $option_price += $option->option_price_tax;
            }
            $entity->fee = ($request->input('course_price_tax')
                    + $option_price + $entity->adjustment_price) * ($entity->fee_rate / 100);
            if ($entity->site_code == 'Special') {
                $hospital_option_plan = HospitalOptionPlan::where('hospital_id', $entity->hospital_id)
                    ->where('option_plan_id', 6)
                    ->where('from', '<=', Carbon::today()->toDateString())
                    ->where(function($q) {
                        $q->whereDate('to', '>=', Carbon::today()->toDateString())
                            ->orWhere('to', '=', null);
                    })->first();

                if ($hospital_option_plan) {
                    $entity->fee = $entity->fee + $hospital_option_plan->pay_per_use_price;
                }
            }
            $entity->is_free_hp_link = 0;
        } elseif ($entity->site_code == 'HP') {
            $entity->fee = $this->getHpfee($hospital);
            if ($entity->fee > 0) {
                $entity->is_free_hp_link = IsFreeHpLink::FREE;
            } else {
                $entity->is_free_hp_link = IsFreeHpLink::FEE;
            }
        }

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

        ReservationOption::where('reservation_id', $reservation_id)->forceDelete();

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
     * 健診システム予約オプション entity生成
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $reservation_id
     * @return Illuminate\Support\Collection
     */
    private function _reservation_kenshin_sys_options_from_request($request, $reservation_id): Collection
    {
        // request options取得
        $options = collect(json_decode(json_encode($request->input('kenshin_sys_option_array'))))->filter(function ($o) {
            return isset($o->option_cd);
        });

        ReservationKenshinSysOption::where('reservation_id', $reservation_id)->forceDelete();
        $results = $options->map(function ($o) use ($reservation_id) {
            $entity = new ReservationKenshinSysOption();
            $entity->reservation_id = $reservation_id;
            $entity->kenshin_sys_option_id = $o->option_id;
            $entity->kenshin_sys_option_no = $o->option_cd;
            $entity->kenshin_sys_option_name = $o->option_name;
            $entity->kenshin_sys_option_price = $o->option_price_tax ?? 0;
            return $entity;
        });

        return $results;
    }

    /**
     * 予約回答登録
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $reservation_id
     */
    private function _reservation_answers_from_request($request, $reservation_id)
    {
        try {
            $q_answers = json_decode(json_encode($request->input('q_anser')));
            $courseQuestions = CourseQuestion::where('course_id', $request->input('course_id'))->get();
            if (! $courseQuestions) {
                return;
            }

            $idx = 1;
            foreach ($courseQuestions as $courseQuestion) {
                foreach ($q_answers as $q_answer) {

                    if ($courseQuestion->question_number == $q_answer->id) {
                        $entity = new ReservationAnswer();
                        $entity->reservation_id = $reservation_id;
                        $entity->course_id = $request->input('course_id');
                        $entity->course_question_id = $q_answer->id;
                        $entity->question_title = $q_answer->question_title;
                        $entity->question_answer01 = $courseQuestion->answer01;
                        $entity->question_answer02 = $courseQuestion->answer02;
                        $entity->question_answer03 = $courseQuestion->answer03;
                        $entity->question_answer04 = $courseQuestion->answer04;
                        $entity->question_answer05 = $courseQuestion->answer05;
                        $entity->question_answer06 = $courseQuestion->answer06;
                        $entity->question_answer07 = $courseQuestion->answer07;
                        $entity->question_answer08 = $courseQuestion->answer08;
                        $entity->question_answer09 = $courseQuestion->answer09;
                        $entity->question_answer10 = $courseQuestion->answer10;

                        foreach ((array)$q_answer->answers as $answer) {
                            if ($idx == 1) {
                                $entity->answer01 = $answer->answer;
                            } elseif ($idx == 2) {
                                $entity->answer02 = $answer->answer;
                            } elseif ($idx == 3) {
                                $entity->answer03 = $answer->answer;
                            } elseif ($idx == 4) {
                                $entity->answer04 = $answer->answer;
                            } elseif ($idx == 5) {
                                $entity->answer05 = $answer->answer;
                            } elseif ($idx == 6) {
                                $entity->answer06 = $answer->answer;
                            } elseif ($idx == 7) {
                                $entity->answer07 = $answer->answer;
                            } elseif ($idx == 8) {
                                $entity->answer08 = $answer->answer;
                            } elseif ($idx == 9) {
                                $entity->answer09 = $answer->answer;
                            } elseif ($idx == 10) {
                                $entity->answer10 = $answer->answer;
                            }

                            $idx += 1;
                        }
                        $entity->save();
                    }
                }

            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error($e);
        }

    }

    private function getApplyPlan(int $hospitalId) {

        $targetDay = Carbon::today();
        return HospitalPlan::where('hospital_id', $hospitalId)
            ->where('from', '<=', $targetDay)
            ->where(function ($query) use ($targetDay) {
                $query->where('to', '>=', $targetDay)
                    ->orWhereNull('to');
            })
            ->first();
    }

    public function getHpfee(Hospital $hospital) {

        $fromDate = Carbon::today();
        $toDate =Carbon::today();

        if (Carbon::today()->day < 21) {
            $fromDate->subMonth();
            $fromDate->day = 21;
            $toDate->day = 20;
        } else {
            $fromDate->day = 21;
            $toDate->addMonth();
            $toDate->day = 20;

        }
        $targets = Reservation::where('hospital_id', $hospital->id)
            ->where('reservation_date', '>=', $fromDate)
            ->where('reservation_date', '<=', $toDate)
            ->where('site_code', 'HP')
            ->count();

        if ($hospital->hplink_contract_type == HplinkContractType::PAY_PAR_USE) {
            $free_count = $hospital->hplink_count;
            if ($targets < $free_count) {
                return 0;
            } else {
                return $hospital->hplink_price;
            }
        } else {
            if ($targets == 0) {
                return $hospital->hplink_price;
            } else {
                return 0;
            }
        }
    }

    private function getClaimMonth(Carbon $reservationDate) {
        if ($reservationDate->day < 21) {
            return $reservationDate->year . sprintf('%02d', $reservationDate->month);
        } else {
            return $reservationDate->year . sprintf('%02d', $reservationDate->addMonth()->month);
        }
    }
}
