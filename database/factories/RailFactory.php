<?php

use Faker\Generator as Faker;

$factory->define(App\Rail::class, function (Faker $faker) {
    return [
        'es_code' => $faker->unique()->randomNumber(),
        'name' => $faker->streetSuffix,
        'status' => $faker->randomElement(['1', 'X'])
    ];
});
