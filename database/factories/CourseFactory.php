<?php

use Faker\Generator as Faker;
use \App\Course;

$factory->define(Course::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'web_reception' => $faker->randomElement(['0', '1']),
        'is_price' => 1,
        'price' => $faker->randomDigit,
        'order' => $faker->numberBetween(1, 100)
    ];
});
