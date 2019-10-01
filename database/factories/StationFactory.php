<?php

use Faker\Generator as Faker;

$factory->define(App\Station::class, function (Faker $faker) {
    return [
        'es_code' => $faker->unique()->randomNumber(),
        'prefecture_id' => $faker->numberBetween(1,47),
        'name' => $faker->streetName,
        'kana' => $faker->streetName,
        'longitude' => $faker->longitude,
        'latitude' => $faker->latitude,
        'status' => $faker->randomElement(['1', 'X']),
    ];
});
