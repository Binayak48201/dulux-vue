<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $roles = config('user.roles');

        $permissions = config('user.permissions');

        foreach ($roles as $role => $key) {
            Role::create(['name' => $role, 'guard_name' => 'api']);
        }

        foreach ($permissions as $permission => $key) {
            Permission::create(['name' => $permission, 'guard_name' => 'api']);
        }

        $superAdmin = Role::whereName('super_admin')->first();

        $superAdmin->givePermissionTo('add');

        $user = User::whereName('superadmin')->first();
        if ($user) {
            $user->assignRole($superAdmin);
        }
    }
}

