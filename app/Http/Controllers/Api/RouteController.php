<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Enums\Status;
use App\Prefecture;
use App\Rail;
use  App\Http\Resources\RouteResource;
use Log;

class RouteController extends ApiBaseController
{
    /**
     * 対象一覧取得（路線）API
     *
     * @param  App\Http\Requests\PlaceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $place_code = $request->input('place_code');
            $rail_no = $request->input('rail_no');

            $place_code_chk = $this->checkPlaceCode($place_code);

            if (!$place_code_chk[0]) {
                return $this->createResponse($place_code_chk[1]);
            }

            $rail_no_chk = $this->checkRailNo($rail_no);

            if (!$rail_no_chk[0]) {
                return $this->createResponse($rail_no_chk[1]);
            }

            $pref = Prefecture::where('code', $place_code)->first();

            $query = Rail::
            join('prefecture_rail', function ($join) use ($place_code) {
                $join->on('rails.id', '=', 'prefecture_rail.rail_id')
                    ->where('prefecture_rail.prefecture_id', $place_code)
                    ->where('prefecture_rail.status', Status::VALID);
            });

            $query->where('rails.status', Status::VALID);

            if (! empty($rail_no)) {
                $query->where('id', $rail_no);
            }

            $routes = $query->get();

            if (!$routes) {
                return $this->createResponse($this->messages['data_empty_error']);
            }

            $data = ['pref' => $pref, 'routes' => $routes];

            return new RouteResource($data);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->createResponse($this->messages['system_error_db']);
        }
    }
}
