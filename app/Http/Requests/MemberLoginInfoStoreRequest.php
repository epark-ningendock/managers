<?php

namespace App\Http\Requests;
use BenSampo\Enum\Rules\EnumValue;
use App\Enums\MailInfoDelivery;
use App\Enums\Contact;
use App\Enums\NickUse;


class MemberLoginInfoStoreRequest extends ValidationRequest
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
            'mail_info_delivery' => 'required| enum_value:' . MailInfoDelivery::class,
            'nick_use' => 'required| enum_value:' . NickUse::class,
            'contact' => 'required| enum_value:' . Contact::class,
            'contact_name' => 'max:32',
        ];

    }

}