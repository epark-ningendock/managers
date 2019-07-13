<?php

use Faker\Generator as Faker;

$factory->define(App\Department::class, function (Faker $faker) {
    $names = [
        '営業部',
        '情報部',
        '人事部',
        '財務部',
        '開発部',
    ];
    return [
        'name' => $names[rand(0, 4)]
    ];
});
