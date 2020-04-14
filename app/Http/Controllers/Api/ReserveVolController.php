<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;


use App\ContractInformation;
use App\Http\Resources\ReserveVolResource;
use App\Http\Requests\ReserveVolRequest;
use App\Hospital;
use Carbon\Carbon;

use Log;


class ReserveVolController extends ApiBaseController
{
    /**
     * 医療機関・検査コース毎の予約数取得API
     *
     * @param  App\Http\Requests\ReserveVolRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index(ReserveVolRequest $request)
    {
        try {
            $hospital_id = $request->input('hospital_no');

            return new ReserveVolResource($this->getHospitalData($hospital_id));
        } catch (\Exception $e) {
            Log::error($e);
            return $this->createResponse($this->messages['system_error_db'], $request->input('callback'));
        }
    }


    private function getHospitalData($hospital_id)
    {
        $today = Carbon::today()->toDateString();
        $from = Carbon::today()->modify("-1 months")->toDateString();

        return Hospital::with([
            'courses' => function ($query) use ($today) {
            $query->where('is_category', 0)
                    ->where('web_reception', 0)
                    ->where('publish_start_date', '<=', $today)
                    ->where('publish_end_date', '>=', $today);
            },
            'reservations' => function ($query) use ($from, $today) {
                $query->where('reservation_date', '>=', $from)
                    ->where('reservation_date', '<=', $today)
                    ->orderBy('reservation_date');
            },
        ])
        ->whereHas('courses' , function($q) use ($today) {
            $q->where('courses.is_category', 0)
            ->where('web_reception', 0)
            ->where('publish_start_date', '<=', $today)
            ->where('publish_end_date', '>=', $today);
        })
        ->find($hospital_id);
    }
}