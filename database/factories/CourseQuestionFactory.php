<?php

use Faker\Generator as Faker;
use App\CourseQuestion;

$factory->define(CourseQuestion::class, function (Faker $faker) {
    $result = [
        'question_number' => $faker->numberBetween(1, 10),
        'is_question' => 1,
        'question_title' => $faker->text(100),
        'answer01' => $faker->text,
        'answer02' => $faker->text,
        'answer03' => $faker->text,
        'answer04' => $faker->text,
        'answer05' => $faker->text,
        'answer06' => $faker->text,
        'answer07' => $faker->text,
        'answer08' => $faker->text,
        'answer09' => $faker->text,
        'answer10' => $faker->text
    ];
    return $result;
});
