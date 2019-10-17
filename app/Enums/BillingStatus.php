<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class BillingStatus extends Enum implements LocalizedEnum
{
	const UNCONFIRMED = 1; // 未確認
	const CHECKING = 2;    // 確認中
	const CONFIRMED = 3;   // 確認済
	const CONFIRM = 4;     // 確定
}
