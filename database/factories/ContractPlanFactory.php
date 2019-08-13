<?php

use Faker\Generator as Faker;
use App\ContractPlan;

$factory->define(ContractPlan::class, function (Faker $faker) {
    return [
        'plan_code' => $faker->unique()->randomElement(['01', '02', '03', '04', '05', '06', '07', '08', '09', '10']),
        'plan_name' => $faker->unique()->userName,
        'fee_rate' => $faker->randomNumber(),
        'monthly_contract_fee' => $faker->randomNumber()
    ];
});
