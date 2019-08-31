<?php

use Faker\Generator as Faker;

$factory->define(App\ContractInformation::class, function (Faker $faker) {
	return [
		'contractor_name_kana' => $faker->name,
		'contractor_name' => $faker->name,
		'application_date' => $faker->dateTime,
		'billing_start_date' => $faker->dateTime,
		'cancellation_date' => $faker->dateTime,
		'representative_name_kana' => $faker->name,
		'representative_name' => $faker->name,
		'postcode' => $faker->postcode,
		'address' => $faker->address,
		'tel' => $faker->phoneNumber,
		'fax' => $faker->phoneNumber,
		'email' => $faker->email,
		'code' => $faker->randomNumber(),
		'contract_plan_id' => $faker->numberBetween(1, 10),
		'property_no' => $faker->word,
		'service_start_date' => $faker->date('Y-m-d'),
		'service_end_date' => $faker->date('Y-m-d'),
	];
});
