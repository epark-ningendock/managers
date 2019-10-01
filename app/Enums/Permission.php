<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class Permission extends Enum implements LocalizedEnum
{
    const NONE = 0;
    const VIEW = 1;
    const EDIT = 3;
    const UPLOAD = 7;

    private const permission_bits = [
        Permission::NONE => 0,
        Permission::VIEW => 1,
        Permission::EDIT => 2,
        Permission::UPLOAD => 4,
    ];

    public function getPermissionBit()
    {
        return self::permission_bits[$this->value];
    }
}
