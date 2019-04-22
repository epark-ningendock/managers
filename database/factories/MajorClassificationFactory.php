<?php

use Faker\Generator as Faker;
use App\MajorClassification;
use App\ClassificationType;

$factory->define(MajorClassification::class, function (Faker $faker) {
    $result = [
        'name' => $faker->userName,
        'status' => '1',
        'order' => 0,
        'is_icon' => $faker->randomElement(['0', '1'])
    ];

    if ($result['is_icon'] == '1') {
        $result['icon_name'] = $faker->userName;
    }

    return $result;
});

$factory->defineAs(MajorClassification::class, 'with_type', function(Faker $faker) use ($factory){
    $major = $factory->raw(MajorClassification::class);
    $type = factory(ClassificationType::class)->create();
    return array_merge($major, ['classification_type_id' => $type->id]);
});