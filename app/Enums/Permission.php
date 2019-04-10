<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class Permission extends Enum implements LocalizedEnum
{
    const None = 0;
    const View = 1;
    const Edit = 3;
    const Upload = 7;

    private const permission_bits = [
        Permission::None => 0,
        Permission::View => 1,
        Permission::Edit => 2,
        Permission::Upload => 4,
    ];

    public function getPermissionBit()
    {
        return self::permission_bits[$this->value];
    }
}
