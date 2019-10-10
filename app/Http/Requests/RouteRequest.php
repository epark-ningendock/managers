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
            'place_code' => 'required|numeric|min:1|max:47',
            'rail_no' => 'nullable|numeric',
        ];

    }

}