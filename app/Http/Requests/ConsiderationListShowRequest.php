<?php

namespace App\Http\Requests;

use App\Http\Requests\ValidationRequest;
use App\Enums\DispKbn;
use App\Enums\NickUse;

class ConsiderationListShowRequest extends ValidationRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'epark_member_id' => 'required|numeric',
            'display_kbn' => 'required|numeric',
        ];

    }

}
