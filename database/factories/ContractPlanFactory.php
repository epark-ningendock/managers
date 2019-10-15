<?php

use Faker\Generator as Faker;
use App\ContractPlan;

$factory->define(ContractPlan::class, function (Faker $faker) {
    return [
        'plan_code' => $faker->unique()->randomElement(['Y001', 'Y002', 'Y003', 'Y004', 'Y005', 'Y006', 'Y007', 'Y008', 'Y009', 'Y010']),
        'plan_name' => $faker->unique(true)->userName,
        'fee_rate' => $faker->numberBetween(0, 100),
        'monthly_contract_fee' => $faker->randomElement([10000, 15000, 20000, 25000, 30000, 50000, 75000, 100000, 120000])
    ];
});
