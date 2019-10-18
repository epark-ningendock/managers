<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Prefecture;
use App\Http\Requests\PlaceRequest;
use App\Http\Resources\PlaceResource;

class PlaceController extends Controller
{
    /**
     * 対象一覧取得（住所）API
     *
     * @param  App\Http\Requests\PlaceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function index(PlaceRequest $request)
    {

        $query = Prefecture::with([
            'district_codes.hospitals',
            'hospitals',
        ]);

        if (! empty($request->input('place_code'))) {
            $query ->where('code', $request->input('place_code'));

        }
        return PlaceResource::collection(
            $query->get()
        );
    }
}
