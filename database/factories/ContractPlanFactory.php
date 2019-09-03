<?php

use Faker\Generator as Faker;
use App\ContractPlan;

$factory->define(ContractPlan::class, function (Faker $faker) {
    return [
        'plan_code' => $faker->unique(true)->randomElement(['01', '02', '03', '04', '05', '06', '07', '08', '09', '10']),
        'plan_name' => $faker->unique(true)->userName,
        'fee_rate' => $faker->numberBetween(0, 100),
        'monthly_contract_fee' => $faker->randomNumber()
    ];
});
