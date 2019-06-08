<?php

use Faker\Generator as Faker;
use App\ImageOrder;

$factory->define(ImageOrder::class, function (Faker $faker) {
    return [
        'image_group_number' => 0,
        'image_location_number' => $faker->numberBetween(1, 10),
        'name' => $faker->userName,
        'order' => $faker->numberBetween(1, 10),
    ];
});
