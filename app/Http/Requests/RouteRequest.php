<?php

namespace App\Http\Requests;

use App\Http\Requests\ValidationRequest;

class RouteRequest extends ValidationRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'place_code' => 'nullable|numeric|min:1|max:47|required_without_all:rail_no',
            'rail_no' => 'nullable|numeric|required_without_all:place_code',
        ];

    }

}