<?php

use App\Models\Brand;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class SalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        factory(Sale::class, 10)->create();
    }
}
