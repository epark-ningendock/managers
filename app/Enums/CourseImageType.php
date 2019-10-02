<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class CourseImageType extends Enum implements LocalizedEnum
{
    const MAIN = '0';
    const PC = '1';
    const SP = '2';
}
