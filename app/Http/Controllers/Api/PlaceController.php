<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Prefecture;
use App\Http\Resources\PlaceResource;
use Log;

class PlaceController extends ApiBaseController
{
    /**
     * 対象一覧取得（住所）API
     *
     * @param  App\Http\Requests\PlaceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        try {
            $place_code_chk_result = $this->checkPlaceCode($request->input('place_code'));

            if (!$place_code_chk_result[0]) {
                return $this->createResponse($place_code_chk_result[1]);
            }

            $query = Prefecture::with([
                'district_codes.hospitals',
                'hospitals',
            ]);

            if (! empty($request->input('place_code'))) {
                $query ->where('prefectures.id', $request->input('place_code'));

            }

            $place_code = $request->input('place_code');
            $place_data = $query->get();

            if (!$place_data) {
                return $this->createResponse($this->messages['data_empty_error']);
            }
            $data = ['place_data' => $place_data, 'place_code' => $place_code];


            return new PlaceResource($data);
        } catch (\Throwable $e) {
            Log::error($e);
            return $this->createResponse($this->messages['system_error_db']);
        }

    }
}
