<?php

namespace App\Http\Controllers\Api;

use App\Course;
use App\CourseFutanCondition;
use App\Enums\AppKbn;
use App\Enums\KenshinSysReservationStatus;
use App\Enums\ReservationStatus;
use App\Enums\Status;
use App\Exceptions\ReservationDateException;
use App\Hospital;
use App\KenshinSysCooperation;
use App\KenshinSysCourseWaku;
use App\KenshinSysDantaiInfo;
use App\Option;
use App\OptionFutanCondition;
use App\OptionTargetAge;
use App\Reservation;
use App\ReservationOption;
use App\TargetAge;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;


class ReservationInfoNotificationController extends Controller
{
    /**
     * 予約情報を登録する
     * @param Request $request
     */
    public function notice(Request $request)
    {
        $messages = config('api.course_info_notification_api.message');
        $sysErrorMessages = config('api.unexpected_error.message');
        $app_name = env('APP_NAME');
        $ip = Request::ip();
        if ($app_name == 'production') {
            $app_kbn = AppKbn::PRODUCTION;
        } else {
            $app_kbn = AppKbn::OTHER;
        }

        // パラメータチェック
        $Ocp_Apim_Subscription_key = $request->input('Ocp-Apim-Subscription-key');
        $partner_code = $request->input('X-Partner-Code');
        if (!isset($Ocp_Apim_Subscription_key)) {
            return $this->createResponse($messages['errorSubscriptionKeyId']);
        }
        if (!isset($partner_code)) {
            return $this->createResponse($messages['errorPartnerCdId']);
        }

        $kenshin_sys_cooperation = KenshinSysCooperation::where('ip', $ip)->first();
        if (!$kenshin_sys_cooperation) {
            return $this->createResponse($messages['errorAccessIp']);
        }

        if ($kenshin_sys_cooperation->app_kbn != $app_kbn) {
            return $this->createResponse($messages['errorAccessIp']);
        }

        if ($kenshin_sys_cooperation->partner_code != $partner_code) {
            return $this->createResponse($messages['errorPartnerCdId']);
        }

        if ($kenshin_sys_cooperation->subscription_key != $Ocp_Apim_Subscription_key) {
            return $this->createResponse($messages['errorSubscriptionKeyId']);
        }

        $hospital = Hospital::where('kenshin_sys_hospital_id')->fist();
        if (!$hospital) {
            return $this->createResponse($messages['errorValidationId']);
        }

        if (empty($request->input('yoyakuNo'))
            || !is_numeric($request->input('yoyakuNo'))
        || strlen($request->input('yoyakuNo')) > 10) {
            return $this->createResponse($messages['errorValidationId']);
        }

        if (empty($request->input('yoyakuStateKbn'))
            || !is_numeric($request->input('yoyakuStateKbn'))
            || !in_array($request->input('yoyakuStateKbn'), [1, 2, 3, 4])) {
            return $this->createResponse($messages['errorValidationId']);
        }

        if ($request->input('yoyakuStateKbn') != 3) {
            if (empty($request->input('yoyakuDate'))
                || !is_numeric($request->input('yoyakuDate'))
                || strlen($request->input('yoyakuDate')) != 8) {
                return $this->createResponse($messages['errorValidationId']);
            }
        }

        DB::beginTransaction();
        try {
            try {
                // 登録
                $this->registReservation($hospital, $request);
            } catch (ReservationDateException $e) {
                return $this->createResponse($sysErrorMessages['errorDataNotFound']);
            }

            DB::commit();
        } catch (\Throwable $e) {
            $message = '[健診システム連携コース通知API] DBの登録に失敗しました。';
            Log::error($message, [
                '健診システム連携情報' => $kenshin_sys_cooperation->toArray(),
                'exception' => $e,
            ]);
            DB::rollback();
            return $this->createResponse($sysErrorMessages['errorDB']);
        }

        return $this->createResponse($messages['success']);
    }

    /**
     * レスポンスを生成する
     *
     * @param array $message
     * @return response
     */
    protected function createResponse( array $message, $statusCode = 200) {
        return response([
            'status_code' => strval($statusCode),
            'result_code' => $message['code'],
            'message' => $message['description'],
        ], $statusCode)
            ->header('Content-Type', 'application/json; charset=utf-8')
            ->header('date', new Carbon());
    }

    /**
     * コース枠情報登録
     * @param array $params
     */
    private function registReservation($hospital, $request) {

        $reservation = Reservation::where('hospital_id', $hospital->id)
            ->where('kenshin_sys_yoyaku_no', $request->input('yoyakuNo'))
            ->first();

        if (!$reservation) {
            throw new ReservationDateException();
        }
        $date = Carbon::parse($request->input('yoyakuDate'));
        $yoyaku_bgn_time_array = explode(':', $request->input('yoyakuBgnTime'));
        if (count($yoyaku_bgn_time_array) == 2) {
            $start_h = $yoyaku_bgn_time_array[0];
            $start_m = $yoyaku_bgn_time_array[1];
        } elseif (strlen($request->input('yoyakuDate')) == 4) {
            $start_h = substr($request->input('yoyakuDate'), 0, 2);
            $start_m = substr($request->input('yoyakuDate'), 2, 2);
        }

        if ($request->input('yoyakuStateKbn') == KenshinSysReservationStatus::PENDING) {
            $reservation->reservation_status = ReservationStatus::PENDING;
            $reservation->reservation_date = $date;
        } elseif ($request->input('yoyakuStateKbn') == KenshinSysReservationStatus::RECEPTION_COMPLETED) {
            $reservation->reservation_status = ReservationStatus::RECEPTION_COMPLETED;
            $reservation->reservation_date = $date;
        } elseif ($request->input('yoyakuStateKbn') == KenshinSysReservationStatus::CANCELLED) {
            $reservation->reservation_status = ReservationStatus::CANCELLED;
            $reservation->cancel_date = Carbon::today();
        } elseif ($request->input('yoyakuStateKbn') == KenshinSysReservationStatus::COMPLETED) {
            $reservation->reservation_status = ReservationStatus::COMPLETED;
            $reservation->reservation_date = $date;
            $reservation->completed_date = $date;
        }

        $reservation->start_time_hour = $start_h;
        $reservation->start_time_min = $start_m;
        $reservation->todays_memo = $request->input('yoyakuComment');
        $reservation->save();

        if (empty($request->input(['optionList']))) {
            return;
        }

        ReservationOption::where('reservation_id', $reservation->id)->delete();

        foreach ($request->input(['optionList']) as $o) {

            $option = Option::where('hospital_id', $hospital->id)
                ->where('kenshin_sys_course_no', $o['optionNo'])
                ->first();

            $reservation_option = new ReservationOption();
            $reservation_option->reservation_id = $reservation->id;
            $reservation_option->option_id = $option->id;
            $reservation_option->option_price = $option->price;
            $reservation_option->status = Status::VALID;
            $reservation_option->save();
        }
    }
}