<?php

namespace App\Http\Requests;

use App\Http\Requests\ValidationRequest;

class CourseRequest extends ValidationRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'hospital_code' => 'required|alpha_num|exists:contract_informations,code',
            'course_no' => 'nullable|numeric|exists:courses,id',
            'course_code' => 'nullable|exists:courses,code',
        ];

    }

}