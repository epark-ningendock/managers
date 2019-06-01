<?php

use Faker\Generator as Faker;

$factory->define(App\HospitalStaff::class, function (Faker $faker) {
	return [
		'name' => $faker->name,
		'email' => $faker->unique()->safeEmail,
		'login_id' => str_random(10),
		'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm',
		'hospital_id' => $faker->numberBetween(1, 50),
	];
});
