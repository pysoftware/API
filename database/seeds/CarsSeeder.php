<?php

use App\Models\Car;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class CarsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        factory(Car::class, 20)->create();
    }
}
