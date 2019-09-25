<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Enums\MailInfoDelivery;
use App\Enums\NickUse;
use App\MemberLoginInfo;
use App\Http\Controllers\Controller;


class MemberLoginInfoController extends Controller
{
    /**
     * ログイン情報登録を実行する
     * @param Request $request
     */
    public function store(Request $request)
    {
        $messages = config('api.member_login_info_api.message');
        $sysErrorMessages = config('api.sys_error.message');
        // パラメータチェック
        if (!isset($request->eparkMemberId) || !is_numeric($request->eparkMemberId)) {
            return $this->createResponse($messages['errorEparkMemberId']);
        }
        if (!isset($request->mailInfoDelivery) || !MailInfoDelivery::hasValue($request->mailInfoDelivery)) {
            return $this->createResponse($messages['errorMailInfoDelivery']);
        }
        if (!isset($request->nickUse) || !NickUse::hasValue($request->nickUse)) {
            return $this->createResponse($messages['errorNickUse']);
        }
        if (!isset($request->contact) || !NickUse::hasValue($request->contact)) {
            return $this->createResponse($messages['errorContact']);
        }
        if (mb_strlen($request->contactName) > 32) {
            return $this->createResponse($messages['errorContactName']);
        }
        if (!isset($request->status) || !NickUse::hasValue($request->status)) {
            return $this->createResponse($messages['errorStatus']);
        }

        $params = [
            'epark_member_id' => $request->eparkMemberId,
            'mail_info_delivery' => $request->mailInfoDelivery,
            'nick_use' => $request->nickUse,
            'contact' => $request->contact,
            'contact_name' => $request->contactName,
            'status' => $request->status,
        ];

        DB::beginTransaction();
        try {
            // 登録
            $this->registMemberLoginInfo($params);
            DB::commit();
        } catch (\Throwable $e) {
            $message = '[EPARK会員ログイン情報API] DBの登録に失敗しました。';
            Log::error($message, [
                'epark_member_id' => $request->eparkMemberId,
                'exception' => $e,
            ]);
            DB::rollback();
            return $this->createResponse($sysErrorMessages['errorDB']);
        }

        return $this->createResponse($messages['success']);
    }

    /**
     * EPARK会員ログイン情報を返す
     * @param Request $request
     */
    public function show(Request $request)
    {
        $messages = config('api.member_login_info_api.message');
        $sysErrorMessages = config('api.sys_error.message');
        // パラメータチェック
        if (!isset($request->eparkMemberId) || !is_numeric($request->eparkMemberId)) {
            return $this->createResponse($messages['errorEparkMemberId']);
        }

        try {
            //
            $memberLoginInfo = MemberLoginInfo::where('epark_member_id', $request->eparkMemberId)->get();
            if (! $memberLoginInfo) {
                return $this->createResponse($messages['errorNotExistInfo']);
            }
        } catch (\Throwable $e) {
            $message = '[EPARK会員ログイン情報API] DB処理に失敗しました。';
            Log::error($message, [
                'epark_member_id' => $request->eparkMemberId,
                'exception' => $e,
            ]);
            return $this->createResponse($sysErrorMessages['errorDB']);
        }

        return $this->createLoginInfoResponse($messages['success'], $memberLoginInfo);

    }

    /**
     * レスポンスを生成する
     *
     * @param array $message
     * @return response
     */
    protected function createResponse(array $message, $statusCode = 200) {
        return response([
            'statusCode' => strval($statusCode),
            'message' => $message['description'],
            'messageId' => $message['code'],
        ], $statusCode)->header('Content-Type', 'application/json; charset=utf-8');
    }

    /**
     * EPARK会員ログイン情報レスポンスを生成する
     *
     * @param array $message
     * @return response
     */
    protected function createLoginInfoResponse(array $message, MemberLoginInfo $memberLoginInfo) {
        return response([
            'statusCode' => strval(200),
            'message' => $message['description'],
            'messageId' => $message['code'],
            'eparkMemberId' => $memberLoginInfo->eparkMemberId,
            'mailInfoDelivery' => $memberLoginInfo->mailInfoDelivery,
            'nickUse' => $memberLoginInfo->nickUse,
            'contact' => $memberLoginInfo->contact,
            'contactName' => $memberLoginInfo->contactName,
            'status' => $memberLoginInfo->status,
        ], 200)->header('Content-Type', 'application/json; charset=utf-8');
    }

    /**
     * ログイン情報登録を行う
     * @param int $hospitalId
     */
    protected function registMemberLoginInfo(array $params) {

        $memberLoginInfo = MemberLoginInfo::where('epark_member_id', $params['epark_member_id'])
            ->first();

        if (! $memberLoginInfo) {
            $memberLoginInfo = new MemberLoginInfo();
        }
        $memberLoginInfo->epark_member_id = $params['epark_member_id'];
        $memberLoginInfo->mail_info_delivery = $params['mail_info_delivery'];
        $memberLoginInfo->nick_use = $params['nick_use'];
        $memberLoginInfo->contact = $params['contact'];
        $memberLoginInfo->contact_name = $params['contact_name'];
        $memberLoginInfo->status = $params['status'];
        $memberLoginInfo->save();
    }
}
