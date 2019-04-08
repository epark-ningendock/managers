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
}
