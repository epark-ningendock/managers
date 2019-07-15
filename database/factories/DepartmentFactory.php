<?php

use Faker\Generator as Faker;

$factory->define(App\Department::class, function (Faker $faker) {

    return [
        'name' => $faker->randomElements([
		        '営業部',
		        '情報部',
		        '人事部',
		        '財務部',
		        '開発部',
	        ])
	    ];
});
