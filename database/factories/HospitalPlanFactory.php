<?php

use App\ContractPlan;
use Faker\Generator as Faker;

$factory->define(App\HospitalPlan::class, function (Faker $faker) {
	$randomDate = random_int(1, 100);
	return [
		'hospital_id' => $faker->numberBetween(1, 50),
		'contract_plan_id' => $faker->randomElement([1, 2, 3, 4, 5]),
		'from' => now()->addDay($randomDate),
		'to' => now()->addDay($randomDate + 21),
	];
});
