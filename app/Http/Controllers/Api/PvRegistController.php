<?php

namespace App\Http\Controllers\Api;

use App\Hospital;
use App\PvRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\PvRegistStoreRequest;

class PvRegistController extends ApiBaseController
{
    /**
     * PV登録を実行する
     * @param Request $request
     */
    public function store(PvRegistStoreRequest $request)
   {
        try {
            $hospitalId = $request->input('hospital_no');
              $this->registPv($hospitalId);
        } catch (\Exception $e) {
            $message = '[PV登録API] DBの登録に失敗しました。';
            Log::error($message, [
                'hospital_no' => $request->input('hospital_no'),
                'exception' => $e,
            ]);
            DB::rollback();
            return $this->createResponse($this->messages['errorDB'], $request->input('callback'));
        }
        return $this->createResponse($this->messages['success'], $request->input('callback'));
    }
    


    /**
     * 指定医療機関IDにてPVデータ登録を行う
     * @param int $hospitalId
     */
    protected function registPv(int $hospitalId) {

        $dateCode = Carbon::today()->format('Ymd');
        $pvRecord = PvRecord::where('hospital_id', '=', $hospitalId)
            ->where('date_code', '=', $dateCode)
            ->first();

        if ($pvRecord) {
            $pvRecord->pv = $pvRecord->pv + 1;
            $pvRecord->save();
        } else {
            $pvRecord = new PvRecord();
            $pvRecord->hospital_id = $hospitalId;
            $pvRecord->date_code = $dateCode;
            $pvRecord->pv = 1;
            $pvRecord->save();
        }
    }
}
