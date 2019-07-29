<?php

use Faker\Generator as Faker;
use \App\CourseDetail;
use \App\Hospital;
use App\MinorClassification;
use App\Course;
use App\CourseQuestion;
use App\CourseImage;

$factory->define(CourseDetail::class, function (Faker $faker) {
    return [
        'inputstring' => $faker->text
    ];
});

$factory->defineAs(CourseDetail::class, 'with_all', function (Faker $faker) use ($factory) {
    $hospital = factory(Hospital::class)->create();
    $minor = MinorClassification::find(1);

    $course = factory(Course::class)->create([
        'hospital_id' => $hospital->id,
        'code' => 'C'.$faker->randomElement(range(1, 100)).'H'.$hospital->id
    ]);

    $detail = $factory->raw(CourseDetail::class);

    factory(CourseQuestion::class)->create([
        'course_id' => $course->id
    ]);

    factory(CourseImage::class)->create([
        'course_id' => $course->id
    ]);


    return array_merge($detail, [
        'course_id' => $course->id,
        'minor_classification_id' => $minor->id,
        'middle_classification_id' => $minor->middle_classification_id,
        'major_classification_id' => $minor->major_classification_id
    ]);
});
