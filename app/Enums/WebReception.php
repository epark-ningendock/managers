<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class WebReception extends Enum implements LocalizedEnum
{
    const ACCEPT = 0;
    const NOT_ACCEPT = 1;
}
