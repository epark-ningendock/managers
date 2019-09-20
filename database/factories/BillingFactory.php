<?php

use App\ContractPlan;
use App\Hospital;
use Faker\Generator as Faker;

$factory->define(App\Billing::class, function (Faker $faker) {
    return [
        'hospital_id' => $faker->numberBetween(1, 50),
        'billing_month' => $faker->date('Y/m'),
        'status' => $faker->numberBetween(1,4),
    ];
});
