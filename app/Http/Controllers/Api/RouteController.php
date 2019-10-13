<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

        $query = Rail::with(
            'prefecture_rails.prefecture.hospitals',
            'rail_station.station'
        );

        if (! empty($rail_no)) {
            $query->where('id', $rail_no);
        }

        $query->wherehas('rail_station.station', function ($q) use ($place_code) {
            $q->where('prefecture_id', $place_code);
        });

        $data = $query->get();

        return RouteResource::collection($data);
    }
}
