<?php

use App\ContractPlan;
use App\Hospital;
use Faker\Generator as Faker;

$factory->define(App\Billing::class, function (Faker $faker) {
    $randomDate = random_int(1, 100);
    return [
        'hospital_id' => $faker->numberBetween(1, 50),
        'contract_plan_id' => factory(ContractPlan::class)->create()->id,
        'from' => now()->addDay($randomDate),
        'to' => now()->addDay($randomDate + 21),
        'status' => $faker->numberBetween(1,4),
	    'created_at' => $faker->dateTimeBetween('-3 months', 'now'),
    ];
});
