<?php

use App\Enums\PermissionType;
use App\Enums\DefaultRoleType;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        /* Permissions */
        // Users
        Permission::create(['name' => PermissionType::VIEW_USERS]);
        Permission::create(['name' => PermissionType::CREATE_USERS]);
        Permission::create(['name' => PermissionType::UPDATE_USERS]);
        Permission::create(['name' => PermissionType::DELETE_USERS]);
        Permission::create(['name' => PermissionType::VIEW_PANEL]);

        /* Roles */
        $userRole = Role::create(['name' => DefaultRoleType::Member]);
        $modRole = Role::create(['name' => DefaultRoleType::Moderator]);
        $adminRole = Role::create(['name' => DefaultRoleType::Administrator]);

        /* Permissions */
        // Moderators have some limited permissions
        $modRole->givePermissionTo([
            PermissionType::VIEW_USERS,
            PermissionType::VIEW_PANEL,
        ]);
        $permissionRefl = new ReflectionClass(PermissionType::class);
        $allDefaultPermissions = array_values((array)$permissionRefl->getConstants());
        // Admin has all the default permissions by default
        $adminRole->givePermissionTo([
            $allDefaultPermissions
        ]);
    }
}
