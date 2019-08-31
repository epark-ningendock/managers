<?php

use Faker\Generator as Faker;
use App\CourseQuestion;

$factory->define(CourseQuestion::class, function (Faker $faker) {
    $result = [
        'question_number' => $faker->numberBetween(1, 10),
        'is_question' => 1,
        'question_title' => $faker->word,
        'answer01' => $faker->word,
        'answer02' => $faker->word,
        'answer03' => $faker->word,
        'answer04' => $faker->word,
        'answer05' => $faker->word,
        'answer06' => $faker->word,
        'answer07' => $faker->word,
        'answer08' => $faker->word,
        'answer09' => $faker->word,
        'answer10' => $faker->word
    ];
    return $result;
});
