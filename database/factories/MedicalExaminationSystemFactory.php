<?php

use App\Department;
use App\Prefecture;
use Faker\Generator as Faker;

$factory->define(App\MedicalExaminationSystem::class, function (Faker $faker) {
    return [
       	'name' => $faker->name,
       	'company_name' => $faker->company,
       	'postcode' => $faker->postcode,
       	'prefecture_id' => factory(Prefecture::class)->create()->id,
       	'address1' => $faker->address,
       	'address2' => $faker->address,
       	'tel' => $faker->phoneNumber,
       	'fax' => $faker->phoneNumber,
       	'staff' => 1, //it should be dynamically created later
       	'department_id' => factory(Department::class)->create()->id,
       	'staff_email' => $faker->email,
    ];
});
