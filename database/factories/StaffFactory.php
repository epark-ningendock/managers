<?php

use Faker\Generator as Faker;
use App\Enums\Authority;

$factory->define(App\Staff::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'login_id' => str_random(10),
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'authority' => Authority::Admin
    ];
});
