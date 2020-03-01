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
        $roleModerator = Role::create(['name' => 'moderator',]);
        $roleWriter = Role::create(['name' => 'writer',]);
        $roleUser = Role::create(['name' => 'user',]);

        /** PERMISSIONS */
        $manageVacanciesPermission = Permission::create(['name' => 'manage vacancies']);
        $manageCvsPermission = Permission::create(['name' => 'manage cvs']);

        $manageAdPermission = Permission::create(['name' => 'manage ad']);
        $manageNewsPermission = Permission::create(['name' => 'manage news']);
        $manageArticlesPermission = Permission::create(['name' => 'manage articles']);

        $publicPermission  = Permission::create(['name' => 'public']);

        $roleAdmin->givePermissionTo([
            $manageAdPermission,
            $manageNewsPermission,
            $manageArticlesPermission,
            $manageVacanciesPermission,
            $manageCvsPermission,
        ]);

        $roleModerator->givePermissionTo([
            $manageVacanciesPermission,
            $manageCvsPermission,
        ]);

        $roleWriter->givePermissionTo([
            $manageAdPermission,
            $manageNewsPermission,
            $manageArticlesPermission,
        ]);

        $roleUser->givePermissionTo([
            $publicPermission,
        ]);
    }
}
