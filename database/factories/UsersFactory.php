<?php

/** @var Factory $factory */

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Faker\Provider\ru_RU\Person;
use Faker\Provider\en_US\Company;

$factory->define(User::class, function (Faker $faker) {
    $faker->addProvider(new Person($faker));
    $fullName = explode(' ', $faker->name);
    return [
        'name' => $fullName[0],
        'last_name' => $fullName[1],
        'patronymic' => $fullName[2],
        'email' => $faker->unique()->safeEmail,
        'password' => Hash::make('password'),
    ];
});
