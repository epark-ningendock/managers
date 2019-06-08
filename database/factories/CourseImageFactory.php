<?php

use Faker\Generator as Faker;
use App\CourseImage;

$factory->define(CourseImage::class, function (Faker $faker) {
    return [
        'image_order_id' => $faker->numberBetween(1, 100)
    ];
});
