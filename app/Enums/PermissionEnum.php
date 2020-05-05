<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PermissionEnum extends Enum
{
    const VIEW_USERS = 'view-users';
    const CREATE_USERS = 'create-users';
    const UPDATE_USERS = 'update-users';
    const DELETE_USERS = 'delete-users';
    const VIEW_DASHBOARD = 'view-dashboard';
    const VIEW_ROLES_PERMISSIONS = 'view-roles-permissions';
    const CREATE_ROLES = 'create-roles';
    const UPDATE_ROLES = 'update-roles';
    const DELETE_ROLES = 'delete-roles';
    const UPDATE_PERMISSIONS = 'update-permissions';
    const ACCESS_TELESCOPE = 'access-telescope';
}
