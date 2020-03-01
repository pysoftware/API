<?php

/** @var Factory $factory */

use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Faker\Provider\ru_RU\Person;
use Faker\Provider\en_US\Company;

$factory->define(Employee::class, function (Faker $faker) {
    $faker->addProvider(new Person($faker));
    $fullName = explode(' ', $faker->name);
    return [
        'name' => $fullName[0],
        'last_name' => $fullName[1],
        'patronymic' => $fullName[2],
        'experience' => $faker->numberBetween(1, 10),
        'salary' => $faker->numberBetween(500, 1000),
    ];
});
