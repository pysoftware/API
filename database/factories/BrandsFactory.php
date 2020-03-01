<?php

/** @var Factory $factory */

use App\Models\Brand;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Faker\Provider\en_US\Company;

$factory->define(Brand::class, function (Faker $faker) {
    $faker->addProvider(new Company($faker));
    return [
        'name' => $faker->unique()->company,
        'country' => $faker->country,
        'plant' => $faker->streetName,
        'address' => $faker->address,
    ];
});
