<?php

namespace App\Http\Controllers\Api;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Prefecture;
use App\Rail;
use App\Http\Requests\RouteRequest;
use  App\Http\Resources\RouteResource;

class RouteController extends Controller
{
    /**
     * 対象一覧取得（路線）API
     *
     * @param  App\Http\Requests\PlaceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function index(RouteRequest $request)
    {
        $place_code = $request->input('place_code');
        $rail_no = $request->input('rail_no');

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

        $data = ['pref' => $pref, 'routes' => $routes];

        return new RouteResource($data);
    }
}
