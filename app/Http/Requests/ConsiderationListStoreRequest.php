<?php

namespace App\Http\Requests;

use App\Http\Requests\ValidationRequest;
use App\Enums\DispKbn;
use App\Enums\NickUse;

class ConsiderationListStoreRequest extends ValidationRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $course_id = $this->input('course_id');

        return [
            'epark_member_id' => 'required|numeric',
            'hospital_id' => 'required|numeric',
            'course_id' => 'numeric',
        ];

    }

}
