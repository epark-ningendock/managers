<?php

use Faker\Generator as Faker;
use App\Enums\Authority;
use App\Staff;

$factory->define(Staff::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'login_id' => str_random(10),
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'authority' => Authority::Member,
        'department_id' => rand(1, 10)
    ];
});
