<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Enums\Status;
use App\Prefecture;
use App\Rail;
use App\Http\Resources\RouteResource;
use App\Http\Requests\RouteRequest;
use Illuminate\Support\Facades\DB;
use Log;

class RouteController extends ApiBaseController
{
    /**
     * 対象一覧取得（路線）API
     *
     * @param  App\Http\Requests\PlaceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function index(RouteRequest $request)
    {
       try {
            $place_code = $request->input('place_code');
            $rail_no = $request->input('rail_no');

            if (empty($place_code)) {
                $pref = $this->getPrefDataByRailNo($rail_no);
            } else {
                $pref = $this->getPrefData($place_code);
            }

            $rails = $this->getRailData($place_code, $rail_no);

            $data = ['pref' => $pref, 'routes' => $rails];

            $result = new RouteResource($data);
            $callback = $request->input('callback');

            return response()->json($result)->setCallback($callback);
       } catch (\Exception $e) {
           Log::error($e);
           return $this->createResponse($this->messages['system_error_db'], $request->input('callback'));
       }
    }

    /**
     * @param $place_code
     * @return mixed
     */
    private function getPrefData($place_code) {

        $select = [
            DB::raw("prefectures.code AS code"),
            DB::raw("prefectures.name AS name"),
            DB::raw("COUNT(hospitals.id) AS hospital_count")
        ];

        $query = Prefecture::query();
        $query->select($select);
        $query->leftJoin('hospitals', 'hospitals.prefecture_id', 'prefectures.id');
        $query->where('prefectures.code', $place_code);
        $query->groupBy('prefectures.code', 'prefectures.name');
        return $query->first();
    }

    /**
     * @param $place_code
     * @return mixed
     */
    private function getPrefDataByRailNo($rain_no) {

        $select = [
            DB::raw("prefectures.code AS code"),
            DB::raw("prefectures.name AS name"),
            DB::raw("COUNT(hospitals.id) AS hospital_count")
        ];

        $query = Prefecture::query();
        $query->select($select);
        $query->join('prefecture_rail', 'prefecture_rail.prefecture_id', 'prefectures.id');
        $query->leftJoin('hospitals', 'hospitals.prefecture_id', 'prefectures.id');
        $query->where('prefecture_rail.rail_id', $rain_no);
        $query->groupBy('prefectures.code', 'prefectures.name');
        return $query->first();
    }

    /**
     * @param $place_code
     * @param $rain_no
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    private function getRailData($place_code, $rain_no) {

        $select = [
            DB::raw("rails.id AS id"),
            DB::raw("rails.name AS name"),
            DB::raw("COUNT(hospitals.id) AS hospital_count")
        ];

        $query = Rail::query();
        $query->select($select);
        $query->join('prefecture_rail', 'prefecture_rail.rail_id', '=', 'rails.id');
        $query->leftJoin('hospitals' , function ($join) {
            $join->on('hospitals.prefecture_id', '=', 'prefecture_rail.prefecture_id')
                ->where(function ($q) {
                    $q->orWhere('hospitals.rail1', '=', 'prefecture_rail.rail_id');
                    $q->orWhere('hospitals.rail2', '=', 'prefecture_rail.rail_id');
                    $q->orWhere('hospitals.rail3', '=', 'prefecture_rail.rail_id');
                    $q->orWhere('hospitals.rail4', '=', 'prefecture_rail.rail_id');
                    $q->orWhere('hospitals.rail5', '=', 'prefecture_rail.rail_id');
                });

        });
        if (!empty($place_code)) {
            $query->where('prefecture_rail.prefecture_id', $place_code);
        }

        if (!empty($rain_no)) {
            $query->where('rails.id', $rain_no);
        }
        $query->where('prefecture_rail.status', Status::VALID);
        $query->where('rails.status', Status::VALID);
        $query->groupBy('rails.id', 'rails.name');

        return $query->get();
    }
}
