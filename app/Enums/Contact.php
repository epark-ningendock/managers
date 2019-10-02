<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class Contact extends Enum implements LocalizedEnum
{
    const HOME = '1';
    const OFFICE = '2';
    const OTHER = '4';
}
