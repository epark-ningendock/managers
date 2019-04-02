<?php

use Faker\Generator as Faker;

$factory->define(\App\StaffAuth::class, function (Faker $faker) {
    return [
        'is_hospital' => $faker->randomElement([0, 1]),
        'is_staff' => $faker->randomElement([0, 1]),
        'is_item_category' => $faker->randomElement([0, 1]),
        'is_invoice' => $faker->randomElement([0, 1]),
        'is_pre_account' => $faker->randomElement([0, 1])
    ];
});
