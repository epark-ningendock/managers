<?php

namespace App\Http\Controllers\Api;

use App\ConsiderationList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ConsiderationListStoreRequest;
use App\Http\Requests\ConsiderationListDestroyRequest;
use App\Http\Requests\ConsiderationListShowRequest;
use App\Http\Resources\ConsiderationListShowResource;

use App\Enums\DispKbn;
use App\Enums\Status;


class ConsiderationListController extends ApiBaseController
{
    /**
     * 検討中リスト登録を実行する
     * @param Request $request
     */
    public function store(ConsiderationListStoreRequest $request)
    {
        $params = [
            'epark_member_id' => $request->epark_member_id,
            'hospital_id' => $request->hospital_id,
            'course_id' => $request->course_id == ''? 0 : $request->course_id,
            'display_kbn' => $request->course_id == ''? DispKbn::FACILITY : DispKbn::COURSE,
            'status' => Status::VALID,
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
            return $this->createResponse($this->messages['errorDB'], $request->input('callback'));
        }
        return $this->createResponse($this->messages['success'], $request->input('callback'));
    }

    /**
     * 検討中リストを返す
     * @param Request $request
     */
    public function show(ConsiderationListShowRequest $request)
    {
        try {
            $results = ConsiderationList::with([
                'contract_informations',
                'course'
            ])
            ->where('epark_member_id', '=', $request->epark_member_id)
            ->where('status', '=', 1)
            ->where('display_kbn', '=', $request->display_kbn)
            ->get();

            return new ConsiderationListShowResource($results);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->createResponse($this->messages['system_error_db'], $request->input('callback'));
        }
    }

    /**
     * 検討中情報を削除する
     * @param Request $request
     */
    public function destroy(ConsiderationListDestroyRequest $request)
    {
        $params = [
            'epark_member_id' => $request->epark_member_id,
            'hospital_id' => $request->hospital_id,
            'course_id' => $request->course_id == ''? 0 : $request->course_id,
            'display_kbn' => $request->course_id == ''? DispKbn::FACILITY : DispKbn::COURSE,
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
            return $this->createResponse($this->messages['errorDB'], $request->input('callback'));
        }
        return $this->createResponse($this->messages['success'], $request->input('callback'));
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
