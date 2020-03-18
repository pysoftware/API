<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        /** ROLES */
        $roleAdmin = Role::create(['name' => 'admin',]);
        $roleCustomer = Role::create(['name' => 'customer',]);
        $roleEmployee = Role::create(['name' => 'employee',]);

        /** PERMISSIONS */
        $createCustomersPermission = Permission::create(['name' => 'create customers']);
        $createEmployeesPermission = Permission::create(['name' => 'create employees']);
        $createCarsPermission = Permission::create(['name' => 'create cars']);
        $createBrandsPermission = Permission::create(['name' => 'create brands']);
        $createSalesPermission = Permission::create(['name' => 'create sales']);

        $updateCustomersPermission = Permission::create(['name' => 'update customers']);
        $updateEmployeesPermission = Permission::create(['name' => 'update employees']);
        $updateCarsPermission = Permission::create(['name' => 'update cars']);
        $updateBrandsPermission = Permission::create(['name' => 'update brands']);
        $updateSalesPermission = Permission::create(['name' => 'update sales']);

        $deleteCustomersPermission = Permission::create(['name' => 'delete customers']);
        $deleteEmployeesPermission = Permission::create(['name' => 'delete employees']);
        $deleteCarsPermission = Permission::create(['name' => 'delete cars']);
        $deleteBrandsPermission = Permission::create(['name' => 'delete brands']);
        $deleteSalesPermission = Permission::create(['name' => 'delete sales']);


        $roleAdmin->givePermissionTo([
            $createCustomersPermission,
            $createEmployeesPermission,
            $createBrandsPermission,
            $createCarsPermission,
            $createSalesPermission,

            $updateCustomersPermission,
            $updateEmployeesPermission,
            $updateBrandsPermission,
            $updateCarsPermission,
            $updateSalesPermission,

            $deleteCustomersPermission,
            $deleteEmployeesPermission,
            $deleteBrandsPermission,
            $deleteCarsPermission,
            $deleteSalesPermission,
        ]);

        $roleCustomer->givePermissionTo([
            $createCustomersPermission,
        ]);

        $roleEmployee->givePermissionTo([
            $createCustomersPermission,
            $createSalesPermission,
            $updateSalesPermission,
            $deleteSalesPermission,
        ]);
    }
}
