<?php

use Faker\Generator as Faker;
use App\ClassificationType;

$factory->define(ClassificationType::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'order' => 0,
        'status' => 1,
        'is_editable' => $faker->randomElement([0, 1])
    ];
});
