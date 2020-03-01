<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(BrandsSeeder::class);
        $this->call(CarsSeeder::class);
        $this->call(EmployeesSeeder::class);
        $this->call(CustomersSeeder::class);

        $this->call(SalesSeeder::class);

        $this->call(PermissionsDatabaseSeeder::class);
        $this->call(UsersDatabaseSeeder::class);
    }
}
