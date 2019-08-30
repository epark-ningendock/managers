<?php

use Faker\Generator as Faker;

$factory->define(App\Station::class, function (Faker $faker) {
    return [
        'es_code' => $faker->unique()->randomNumber(),
        'prefecture_id' => factory(\App\Prefecture::class)->create()->id,
        'name' => $faker->streetName,
        'kana' => $faker->streetName,
        'longitude' => $faker->longitude,
        'latitude' => $faker->latitude,
        'status' => $faker->randomElement(['1', 'X']),
    ];
});
