<?php

use App\Enums as Enums;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models as SpatiePermissionModels;

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
        SpatiePermissionModels\Permission::create(['name' => Enums\PermissionEnum::VIEW_DASHBOARD]);
        // Users
        SpatiePermissionModels\Permission::create(['name' => Enums\PermissionEnum::VIEW_USERS]);
        SpatiePermissionModels\Permission::create(['name' => Enums\PermissionEnum::CREATE_USERS]);
        SpatiePermissionModels\Permission::create(['name' => Enums\PermissionEnum::UPDATE_USERS]);
        SpatiePermissionModels\Permission::create(['name' => Enums\PermissionEnum::DELETE_USERS]);
        // Roles & Permissions
        SpatiePermissionModels\Permission::create(['name' => Enums\PermissionEnum::VIEW_ROLES_PERMISSIONS]);
        SpatiePermissionModels\Permission::create(['name' => Enums\PermissionEnum::CREATE_ROLES]);
        SpatiePermissionModels\Permission::create(['name' => Enums\PermissionEnum::UPDATE_ROLES]);
        SpatiePermissionModels\Permission::create(['name' => Enums\PermissionEnum::DELETE_ROLES]);
        SpatiePermissionModels\Permission::create(['name' => Enums\PermissionEnum::UPDATE_PERMISSIONS]);

        /* Roles */
        $adminRole = SpatiePermissionModels\Role::create(['name' => Enums\DefaultRoleEnum::ADMINISTRATOR]); // Highest role should be the first role
        $modRole = SpatiePermissionModels\Role::create(['name' => Enums\DefaultRoleEnum::MODERATOR]);
        $memberRole = SpatiePermissionModels\Role::create(['name' => Enums\DefaultRoleEnum::MEMBER]);

        /* Permissions */
        $permissionRefl = new ReflectionClass(Enums\PermissionEnum::class);
        $allDefaultPermissions = array_values((array)$permissionRefl->getConstants());
        // Admin has all the default permissions by default
        $adminRole->givePermissionTo([
            $allDefaultPermissions
        ]);
         // Moderators have some limited permissions
         $modRole->givePermissionTo([
            Enums\PermissionEnum::VIEW_USERS,
            Enums\PermissionEnum::VIEW_DASHBOARD,
        ]);
        // Attach permissions to Member here if necessary
    }
}
