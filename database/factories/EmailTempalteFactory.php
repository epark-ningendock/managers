<?php

use Faker\Generator as Faker;
use App\Staff;

$factory->define(Model::class, function (Faker $faker) {
    return [
        'title' => $faker->name,
        'text' => $faker->name,
    ];
});
