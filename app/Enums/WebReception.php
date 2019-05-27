<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class WebReception extends Enum implements LocalizedEnum
{
    const NotAccept = 0;
    const Accept = 1;
}
