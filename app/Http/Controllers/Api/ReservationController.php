<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ReservationShowRequest;
use App\Http\Requests\ReservationStoreRequest;
use App\Http\Requests\ReservationCancelRequest;

use App\Services\ReservationService;

use App\Http\Resources\ReservationConfResource;
use App\Http\Resources\ReservationStoreResource;

use App\Exceptions\ReservationUpdateException;


class ReservationController extends ApiBaseController
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
        // パラメータ取得
        $reservation_id = $request->input('reservation_id');

        // 対象取得
        $entity = $this->_reservation_service->find($reservation_id);

        // 変更可確認
        $entity->result_code = $this->_reservation_service->isCancel($entity);

        // response set
        return new ReservationConfResource($entity);
    }

    /**
     * 予約キャンセルAPI
     *
     * @param  App\Http\Requests\ReservationCancelRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function cancel(ReservationCancelRequest $request)
    {
        // パラメータ取得
        $reservation_id = $request->input('reservation_id');

        // 対象取得
        $entity = $this->_reservation_service->find($reservation_id);

        // キャンセル有効期間チェック
        $entity->result_code = $this->_reservation_service->isCancel($entity);
        if ($entity->result_code === 1) { // キャンセル不可
            throw new ReservationUpdateException();
        }

        // 予約履歴apiより予約履歴をキャンセル実行する。
        $epark = $this->_reservation_service->request($request);

        // 予約テーブルのステータスをキャンセルへ更新する。
        $entity->reservation_status = 4; // キャンセル
        $this->_reservation_service->update($entity);

        // メール送信フラグをentityに追加
        $entity->mail_fg = intval($request->input('mail_fg'));

        // メール送信
        $entity->result_code = $this->_reservation_service->mail($entity);

        // response set
        $status = 0;
        $result_code = $entity->result_code;
        $reservation_id = $entity->id;
        return compact('status', 'result_code', 'reservation_id');
    }

    /**
     * 予約登録API
     *
     * @param  App\Http\Requests\ReservationStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReservationStoreRequest $request)
    {
        // 予約可能かチェック
        $this->_reservation_service->isReservation($request);
        
        // 予約登録／更新
        $entity = $this->_reservation_service->store($request);

        // 予約履歴apiより予約履歴登録を実行する。
        $this->_reservation_service->request($request, $entity);

        // 処理区分をentityに追加
        $entity->process_kbn = intval($request->input('process_kbn'));

        // 完了メール送信
        $entity->result_code = $this->_reservation_service->mail($entity);

        return new ReservationStoreResource($entity);
    }
}
