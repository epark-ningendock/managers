<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Prefecture;
use App\Http\Resources\PlaceResource;
use App\Http\Requests\PlaceRequest;

use Log;

class PlaceController extends ApiBaseController
{
    /**
     * 対象一覧取得（住所）API
     *
     * @param  App\Http\Requests\PlaceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function index(PlaceRequest $request)
    {

        try {
            $query = Prefecture::with([
                'district_codes',
                'district_codes.hospitals',
                'hospitals',
            ]);
            if ($request->input('place_code') != 0) {
                $query ->where('prefectures.id', $request->input('place_code'));

            }
            $place_code = $request->input('place_code');
            $place_data = $query->get();

            $data = ['place_data' => $place_data, 'place_code' => $place_code];

            $result = new PlaceResource($data);
            $callback = $request->input('callback');

            return response()->json($result)->setCallback($callback);
        } catch (\Throwable $e) {
            Log::error($e);
            return $this->createResponse($this->messages['system_error_db'], $request->input('callback'));
        }

    // }
  }

}

