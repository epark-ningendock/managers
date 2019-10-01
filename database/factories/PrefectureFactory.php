<?php

use App\Prefecture;
use Faker\Generator as Faker;

$factory->define(Prefecture::class, function (Faker $faker) {
    return [
        'code' => $faker->randomNumber(),
        'name' => $faker->name,
    ];
});
