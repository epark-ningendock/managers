<?php

namespace App\Http\Controllers\Api;

use App\Enums\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Enums\MailInfoDelivery;
use App\Enums\NickUse;
use App\Enums\Status;
use App\MemberLoginInfo;
use App\Http\Requests\MemberLoginInfoRequest;
use App\Http\Requests\MemberLoginInfoStoreRequest;


class MemberLoginInfoController extends ApiBaseController
{
    /**
     * ログイン情報登録を実行する
     * @param Request $request
     */
    public function store(MemberLoginInfoStoreRequest $request)
    {
        $params = [
            'epark_member_id' => $request->epark_member_id,
            'mail_info_delivery' => $request->mail_info_delivery,
            'nick_use' => $request->nick_use,
            'contact' => $request->contact,
            'contact_name' => $request->contact_name,
            'status' => Status::VALID,
        ];

        DB::beginTransaction();
        try {
            // 登録
            $this->registMemberLoginInfo($params);
            DB::commit();
        } catch (\Throwable $e) {
            $message = '[EPARK会員ログイン情報API] DBの登録に失敗しました。';
            Log::error($message, [
                'epark_member_id' => $request->epark_member_id,
                'exception' => $e,
            ]);
            DB::rollback();
            return $this->createResponse($this->messages['errorDB'], $request->input('callback'));
        }

        return $this->createResponse($this->messages['success'], $request->input('callback'));
    }

    /**
     * EPARK会員ログイン情報を返す
     * @param Request $request
     */
    public function show(MemberLoginInfoRequest $request)
    {
        try { 
            $memberLoginInfo = MemberLoginInfo::where('epark_member_id', $request->epark_member_id)
                ->where('status', Status::VALID)
                ->first();
        } catch (\Throwable $e) {
            $message = '[EPARK会員ログイン情報API] DB処理に失敗しました。';
            Log::error($message, [
                'epark_member_id' => $request->epark_member_id,
                'exception' => $e,
            ]);
            return $this->createResponse($this->messages['errorDB'], $request->input('callback'));
        }

        return $this->createLoginInfoResponse($this->messages['success'], $memberLoginInfo);

    }

    /**
     * EPARK会員ログイン情報レスポンスを生成する
     *
     * @param array $message
     * @param MemberLoginInfo $memberLoginInfo
     * @return response
     */
    protected function createLoginInfoResponse(array $message, MemberLoginInfo $memberLoginInfo) {
        return response([
            'status' =>0,
            'message' => $message['message'],
            'messageId' => $message['message_id'],
            'no' => $memberLoginInfo->id,
            'epark_member_id' => $memberLoginInfo->epark_member_id,
            'mail_info_delivery' => $memberLoginInfo->mail_info_delivery,
            'nick_use' => $memberLoginInfo->nick_use,
            'contact' => $memberLoginInfo->contact,
            'contact_name' => $memberLoginInfo->contact_name,
        ], 200)->header('Content-Type', 'application/json; charset=utf-8');
    }

    /**
     * ログイン情報登録を行う
     * @param array $params
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
