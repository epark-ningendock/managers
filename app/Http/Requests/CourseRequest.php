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
            'hospital_code' => ['required','regex:/^D[0-9a-zA-Z]+$/u','exists:contract_informations,code'],
            'course_code' => 'required|exists:courses,code',
        ];

    }

}