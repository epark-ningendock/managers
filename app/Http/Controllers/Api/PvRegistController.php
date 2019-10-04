<?php

namespace App\Http\Controllers\Api;

use App\Hospital;
use App\PvRecord;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PvRegistController extends Controller
{
    /**
     * PV登録を実行する
     * @param Request $request
     */
    public function store(Request $request)
    {
        $hospitalId = $request->input('hospitalId');
        if (!empty($hospitalId) && $this->isExistHospital($hospitalId)) {
            // PVデータ更新
            $this->registPv($hospitalId);
        }
    }

    /**
     * 医療機関存在チェック
     * @param int $hospitalId
     */
    protected function isExistHospital(int $hospitalId) {

       $hospital = Hospital::find($hospitalId);

       if (empty($hospital)) {
           return false;
       }

       return true;
    }

    /**
     * 指定医療機関IDにてPVデータ登録を行う
     * @param int $hospitalId
     */
    protected function registPv(int $hospitalId) {

        $dateCode = $this->createDateCode();
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

    /**
     * 日付コードを生成する
     */
    protected function createDateCode() {

        $date = Carbon::now();

        return $date->year . sprintf('%02d', $date->month) . sprintf('%02d', $date->day);
    }
}