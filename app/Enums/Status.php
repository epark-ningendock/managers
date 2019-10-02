<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class Status extends Enum implements LocalizedEnum
{
    const VALID = '1';
    const DELETED = 'X';
}
