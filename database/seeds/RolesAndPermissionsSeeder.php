<?php

use App\Enums\PermissionEnum;
use App\Enums\DefaultRoleEnum;
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
        // Dashboard
        Permission::create(['name' => PermissionEnum::VIEW_DASHBOARD]);
        // Users
        Permission::create(['name' => PermissionEnum::VIEW_USERS]);
        Permission::create(['name' => PermissionEnum::CREATE_USERS]);
        Permission::create(['name' => PermissionEnum::UPDATE_USERS]);
        Permission::create(['name' => PermissionEnum::DELETE_USERS]);
        // Roles & Permission
        Permission::create(['name' => PermissionEnum::VIEW_ROLES_PERMISSIONS]);
        Permission::create(['name' => PermissionEnum::CREATE_ROLES]);
        Permission::create(['name' => PermissionEnum::UPDATE_ROLES]);
        Permission::create(['name' => PermissionEnum::DELETE_ROLES]);
        Permission::create(['name' => PermissionEnum::UPDATE_PERMISSIONS]);

        /* Roles */
        $adminRole = Role::create(['name' => DefaultRoleEnum::ADMINISTRATOR]); // Highest role should be the first role
        $modRole = Role::create(['name' => DefaultRoleEnum::MODERATOR]);
        $userRole = Role::create(['name' => DefaultRoleEnum::MEMBER]);

        /* Permissions */
        // Moderators have some limited permissions
        $modRole->givePermissionTo([
            PermissionEnum::VIEW_USERS,
            PermissionEnum::VIEW_DASHBOARD,
        ]);
        $permissionRefl = new ReflectionClass(PermissionEnum::class);
        $allDefaultPermissions = array_values((array)$permissionRefl->getConstants());
        // Admin has all the default permissions by default
        $adminRole->givePermissionTo([
            $allDefaultPermissions
        ]);
    }
}
