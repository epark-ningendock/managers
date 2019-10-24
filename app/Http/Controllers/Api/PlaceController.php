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
            $query ->where('prefectures.id', $request->input('place_code'));

        }

        $place_code = $request->input('place_code');
        $place_data = $query->get();
        $data = ['place_data' => $place_data, 'place_code' => $place_code];


        return new PlaceResource($data);
    }
}
