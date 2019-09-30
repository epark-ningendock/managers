<?php

namespace App\Http\Controllers\Api;

use App\ConsiderationList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Enums\DispKbn;
use App\Enums\NickUse;
use App\Enums\Status;
use App\MemberLoginInfo;
use App\Http\Controllers\Controller;


class ConsiderationListController extends Controller
{
    /**
     * 検討中リスト登録を実行する
     * @param Request $request
     */
    public function store(Request $request)
    {
        $messages = config('api.consideration_list_api.message');
        $sysErrorMessages = config('api.sys_error.message');
        // パラメータチェック
        if (!isset($request->epark_member_id) || !is_numeric($request->epark_member_id)) {
            return $this->createResponse($messages['errorEparkMemberId']);
        }
        if (isset($request->hospital_id) && !is_numeric($request->hospital_id)) {
            return $this->createResponse($messages['errorHospitalId']);
        }
        if (isset($request->course_id) && !is_numeric($request->course_id)) {
            return $this->createResponse($messages['errorCourseId']);
        }
        if (!isset($request->display_kbn) || !DispKbn::hasValue($request->display_kbn)) {
            return $this->createResponse($messages['errorDisplayKbn']);
        }
        if (!isset($request->status) || !NickUse::hasValue($request->status)) {
            return $this->createResponse($messages['errorStatus']);
        }

        $params = [
            'epark_member_id' => $request->epark_member_id,
            'hospital_id' => $request->hospital_id,
            'course_id' => $request->course_id,
            'display_kbn' => $request->display_kbn,
            'status' => $request->status,
        ];

        DB::beginTransaction();
        try {
            // 登録
            $this->regist($params);
            DB::commit();
        } catch (\Throwable $e) {
            $message = '[検討中リストAPI] DBの登録に失敗しました。';
            Log::error($message, [
                'epark_member_id' => $request->epark_member_id,
                'exception' => $e,
            ]);
            DB::rollback();
            return $this->createResponse($sysErrorMessages['errorDB']);
        }

        return $this->createResponse($messages['success']);
    }

    /**
     * 検討中リストを返す
     * @param Request $request
     */
    public function show(Request $request)
    {
        $messages = config('api.consideration_list_api.message');
        $sysErrorMessages = config('api.sys_error.message');
        // パラメータチェック
        if (!isset($request->epark_member_id) || !is_numeric($request->epark_member_id)) {
            return $this->createResponse($messages['errorEparkMemberId']);
        }

        try {
            //
            $results = MemberLoginInfo::where('epark_member_id', $request->epark_member_id)
                ->where('status', Status::Valid)
                ->get();
            if (! $results) {
                return $this->createResponse($messages['errorNotExistInfo']);
            }
        } catch (\Throwable $e) {
            $message = '[検討中リストAPI] DB処理に失敗しました。';
            Log::error($message, [
                'epark_member_id' => $request->epark_member_id,
                'exception' => $e,
            ]);
            return $this->createResponse($sysErrorMessages['errorDB']);
        }

        return $this->createConsiderationListResponse($messages['success'], $results);

    }

    /**
     * 検討中情報を削除する
     * @param Request $request
     */
    public function destroy(Request $request)
    {
        $messages = config('api.consideration_list_api.message');
        $sysErrorMessages = config('api.sys_error.message');
        // パラメータチェック
        if (!isset($request->epark_member_id) || !is_numeric($request->epark_member_id)) {
            return $this->createResponse($messages['errorEparkMemberId']);
        }
        if (!isset($request->display_kbn) || !DispKbn::hasValue($request->display_kbn)) {
            return $this->createResponse($messages['errorDisplayKbn']);
        }

        if (Disp::FACILITY == $request->display_kbn) {
            if (! isset($request->hospital_id) || !is_numeric($request->hospital_id)) {
                return $this->createResponse($messages['errorHospitalId']);
            }
        } else {
            if (!isset($request->course_id) || !is_numeric($request->course_id)) {
                return $this->createResponse($messages['errorCourseId']);
            }
        }

        $params = [
            'epark_member_id' => $request->epark_member_id,
            'hospital_id' => $request->hospital_id,
            'course_id' => $request->course_id,
            'display_kbn' => $request->display_kbn,
        ];

        DB::beginTransaction();
        try {
            // 削除
            $this->delete($params);
            DB::commit();
        } catch (\Throwable $e) {
            $message = '[検討中リストAPI] DBの削除に失敗しました。';
            Log::error($message, [
                'epark_member_id' => $request->epark_member_id,
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
     * @param $statusCode
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
    protected function createConsiderationListResponse(array $message, array $results) {

        $params = [];
        foreach ($results as $result) {
            $param = [
              'epark_member_id' => $result->epark_member_id,
                'hospital_id' => $result->hospital_id,
                'course_id' => $result->course_id,
                'display_kbn' => $result->display_kbn,
                'status' => $result->status
            ];
            $params = $param;
        }
        return response([
            'status_code' => strval(200),
            'message' => $message['description'],
            'message_id' => $message['code'],
            'data' => $params,
        ], 200)->header('Content-Type', 'application/json; charset=utf-8');
    }

    /**
     * 登録を行う
     * @param int $hospitalId
     */
    protected function regist(array $params) {

        $target = ConsiderationList::where('epark_member_id', $params['epark_member_id'])
            ->where('hospital_id', $params['hospital_id'])
            ->where('course_id', $params['course_id'])
            ->where('display_kbn', $params['display_kbn'])
            ->first();

        if (! $target) {
            $target = new ConsiderationList();
        }
        $target->epark_member_id = $params['epark_member_id'];
        $target->hospital_id = $params['hospital_id'];
        $target->course_id = $params['course_id'];
        $target->display_kbn = $params['display_kbn'];
        $target->status = $params['status'];
        $target->save();
    }

    /**
     * 削除を行う
     * @param int $hospitalId
     */
    protected function delete(array $params) {

        $targets = ConsiderationList::where('epark_member_id', $params['epark_member_id'])
            ->where('hospital_id', $params['hospital_id'])
            ->where('course_id', $params['course_id'])
            ->where('display_kbn', $params['display_kbn'])
            ->get();

        if (! $targets) {
            return;
        }

        foreach ($targets as $target) {
            $target->delete();
        }
    }
}
