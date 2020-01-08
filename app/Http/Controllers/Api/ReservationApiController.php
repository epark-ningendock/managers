<?php

namespace App\Http\Controllers\Api;

use App\ConvertedId;
use App\Enums\ReservationStatus;
use App\Http\Requests\ReservationShowRequest;
use App\Http\Requests\ReservationStoreRequest;
use App\Http\Requests\ReservationCancelRequest;

use App\Services\ReservationService;

use App\Http\Resources\ReservationConfResource;
use App\Http\Resources\ReservationStoreResource;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Exceptions\HttpResponseException;

class ReservationApiController extends ApiBaseController
{
    private $_reservation_service;

    /**
     * constructor
     *
     * @param  App\Services\ReservationService $reservation_service
     */
    public function __construct(ReservationService $reservation_service)
    {
        $this->_reservation_service = $reservation_service;
    }

    /**
     * 予約確認API
     *
     * @param  App\Http\Requests\ReservationShowRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function conf(ReservationShowRequest $request)
    {
        try {
            // パラメータ取得
            $reservation_id = $request->input('reservation_id');
            $reservation_id = $this->convertReservationId($reservation_id);

            // 対象取得
            $entity = $this->_reservation_service->find($reservation_id);

            // 変更可確認
            $entity->result_code = $this->_reservation_service->isCancel($entity);

            // response set
            return new ReservationConfResource($entity);
        } catch (\Exception $e) {
            Log::error('予約情報取得に失敗しました。:'. $e);
            $this->failedResult(['00', '01', '内部エラー']);
        }

    }

    /**
     * 予約キャンセルAPI
     *
     * @param  App\Http\Requests\ReservationCancelRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function cancel(ReservationCancelRequest $request)
    {
        try {
            // パラメータ取得
            $reservation_id = $request->input('reservation_id');
            $reservation_id = $this->convertReservationId($reservation_id);

            // 対象取得
            $entity = $this->_reservation_service->find($reservation_id);

            // キャンセル有効期間チェック
            $result_code = $this->_reservation_service->isCancel($entity);
            if ($result_code === 1) { // キャンセル不可
                $this->failedResult(['04', '12', '予約ステータス更新エラー']);
            }

            // 予約テーブルのステータスをキャンセルへ更新する。
            $entity->reservation_status = ReservationStatus::CANCELLED; // キャンセル
            $entity->cancel_date = Carbon::now();

            try {
                // 予約履歴apiより予約履歴をキャンセル実行する。
                $this->_reservation_service->request($request, $entity);
            } catch (\Exception $e) {
                Log::error('予約履歴API実行に失敗しました。:'. $e);
            }

            $entity->save();

            // カレンダーの予約数を1つ減らす
            $this->_reservation_service->registReservationToCalendar($entity, -1);

            // メール送信フラグをentityに追加
            $entity->mail_fg = intval($request->input('mail_fg'));

            // メール送信
            $entity->result_code = $this->_reservation_service->mail($entity);

            // response set
            $status = 0;
            $result_code = $entity->result_code;
            $reservation_id = $entity->id;
            return compact('status', 'result_code', 'reservation_id');
        } catch (\Exception $e) {
            Log::error('予約キャンセル登録に失敗しました。:'. $e);
            $this->failedResult(['00', '01', '内部エラー']);
        }

    }

    /**
     * @param $id
     * @return mixed
     */
    private function convertReservationId($id) {
        $reservation_id = $id;

        // 旧予約IDの場合、新予約IDへ変換
        if(strpos($reservation_id,'_') !== false){
            $old_ids = explode('_', $reservation_id);
            $converted = ConvertedId::where('table_name', 'reservations')
                ->where('old_id', $old_ids[1])
                ->where('hospital_no', $old_ids[0])
                ->first();
            $reservation_id = $converted->new_id;
        }

        return $reservation_id;
    }

    /**
     * 予約登録API
     *
     * @param  App\Http\Requests\ReservationStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReservationStoreRequest $request)
    {
//        try {
            $reservation_id = $request->input('reservation_id');
            $reservation_id = $this->convertReservationId($reservation_id);
            // 予約可能かチェック
            $result = $this->_reservation_service->isReservation($request, $reservation_id);

            if ($result == 1) {
                $this->failedResult(['00', '04', '登録失敗（予約枠なし(予約済みエラー、受付数オーバーエラー)）']);
            } elseif ($result == 2 || $result == 4) {
                $this->failedResult(['00', '04', '登録失敗（予約枠なし(予約済みエラー、受付数オーバーエラー)）']);
            } elseif ($result == 3) {
                $this->failedResult(['00', '05', '登録失敗（予約不可(必須項目エラー、引数エラー、顧客チェック失敗エラー、その他エラー、メール送信エラー）']);
            } elseif ($result == 5) {
                $this->failedResult(['00', '03', '登録失敗（予約枠埋まり)']);
            }

            // 予約登録／更新
            $entity = $this->_reservation_service->store($request);
            // カレンダーの予約数を1つ増やす
            $this->_reservation_service->registReservationToCalendar($entity, 1);

            try {
                // 予約履歴apiより予約履歴登録を実行する。
                $this->_reservation_service->request($request, $entity);
            } catch (\Exception $e) {
                Log::error('予約履歴API実行に失敗しました。:'. $e);
            }

            // 処理区分をentityに追加
            $entity->process_kbn = intval($request->input('process_kbn'));

            // 完了メール送信
            $entity->result_code = $this->_reservation_service->mail($entity);

            return new ReservationStoreResource($entity);
//        } catch (\Exception $e) {
//            Log::error('予約登録処理に失敗しました。:'. $e);
//            $this->failedResult(['00', '01', '内部エラー']);
//        }

    }

    /**
     * @param $error
     */
    private function failedResult($error)
    {
        $callback = 'fbfunc';
        $status = 1;
        $error_no = $error[0];
        $detail_code = $error[1];
        $message = $error[2];

        $response['status']  = $status;
        $response['code_number']  = $error_no;
        $response['code_detail']  = $detail_code;
        $response['message']  = $message;
        throw new HttpResponseException(
            response()->json($response, 400)->setCallback($callback)
        );
    }
}
