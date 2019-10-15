<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class SelectPhotoFlag extends Enum implements LocalizedEnum
{
  const UNSELECTED = 0; // 写真未選択
  const SELECTED = 1; // 写真選択
}
