<?php

use Faker\Generator as Faker;
use App\HospitalImage;

$factory->define(HospitalImage::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->userName,
        'extension' => 'jpg',
        'path' => '/images/hospitals/'
    ];
});
