<?php

use Faker\Generator as Faker;
use \App\StaffAuth;
use \App\Staff;

$factory->define(StaffAuth::class, function (Faker $faker) {
    return [
        'is_hospital' => $faker->randomElement([0, 1]),
        'is_staff' => $faker->randomElement([0, 1]),
        'is_item_category' => $faker->randomElement([0, 1]),
        'is_invoice' => $faker->randomElement([0, 1]),
        'is_pre_account' => $faker->randomElement([0, 1])
    ];
});

$factory->defineAs(StaffAuth::class, 'with_staff', function (Faker $faker) use ($factory) {
    $staff_auth = $factory->raw(StaffAuth::class);
    $staff = factory(Staff::class)->create();
    return array_merge($staff_auth, ['staff_id' => $staff->id]);
});
