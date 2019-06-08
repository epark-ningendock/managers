<?php

use Faker\Generator as Faker;
use App\Option;

$factory->define(Option::class, function (Faker $faker) {
    return [
        'name' => $faker->userName,
        'price' => $faker->randomDigit,
        'order' => $faker->numberBetween(1, 100)
    ];
});
