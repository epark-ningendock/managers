<?php

namespace App\Http\Requests;


class MemberLoginInfoRequest extends ValidationRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'epark_member_id' => 'required|numeric|exists:member_login_infos,epark_member_id',
        ];

    }

}