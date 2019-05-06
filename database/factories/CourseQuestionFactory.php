<?php

use Faker\Generator as Faker;
use App\CourseQuestion;

$factory->define(CourseQuestion::class, function (Faker $faker) {
    $result = [
        'question_number' => $faker->numberBetween(1, 10),
        'is_question' => 1,
        'question_title' => $faker->text(100)
    ];
    if ($result['question_number'] >= 1) {
        $result['answer01'] = $faker->text;
    }
    if ($result['question_number'] >= 2) {
        $result['answer02'] = $faker->text;
    }
    if ($result['question_number'] >= 3) {
        $result['answer03'] = $faker->text;
    }
    if ($result['question_number'] >= 4) {
        $result['answer04'] = $faker->text;
    }
    if ($result['question_number'] >= 5) {
        $result['answer05'] = $faker->text;
    }
    if ($result['question_number'] >= 6) {
        $result['answer06'] = $faker->text;
    }
    if ($result['question_number'] >= 7) {
        $result['answer07'] = $faker->text;
    }
    if ($result['question_number'] >= 8) {
        $result['answer08'] = $faker->text;
    }
    if ($result['question_number'] >= 9) {
        $result['answer09'] = $faker->text;
    }
    if ($result['question_number'] == 10) {
        $result['answer10'] = $faker->text;
    }

    return $result;
});
