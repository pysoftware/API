<?php

/** @var Factory $factory */

use App\Models\Brand;
use App\Models\Car;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Sale;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Faker\Provider\en_US\Company;

$factory->define(Sale::class, function (Faker $faker) {
    $faker->addProvider(new Company($faker));
    return [
        'employee_id' => Employee::inRandomOrder()->get()->pluck('id')->first(),
        'car_id' => Car::inRandomOrder()->get()->pluck('id')->first(),
        'customer_id' => Customer::inRandomOrder()->get()->pluck('id')->first(),
    ];
});
