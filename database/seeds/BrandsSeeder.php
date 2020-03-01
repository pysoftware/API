<?php

use App\Models\Brand;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class BrandsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        factory(Brand::class, 20)->create();
    }
}
