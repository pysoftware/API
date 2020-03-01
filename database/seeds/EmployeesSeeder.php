<?php

use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class EmployeesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        factory(Employee::class, 10)->create();
    }
}
