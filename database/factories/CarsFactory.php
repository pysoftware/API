<?php

/** @var Factory $factory */

use App\Models\Brand;
use App\Models\Car;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Faker\Provider\en_US\Company;

$factory->define(Car::class, function (Faker $faker) {
    $faker->addProvider(new Company($faker));
    return [
        'name' => $faker->unique()->company,
        'release_year' => $faker->date(),
        'color' => $faker->colorName,
        'category' => $faker->domainName,
        'price' => $faker->numberBetween(1000, 2000),
        'brand_id' => Brand::inRandomOrder()->get()->pluck('id')->first(),
    ];
});
