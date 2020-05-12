<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PermissionEnum extends Enum
{
    const READ_USERS = 'read-users';
    const CREATE_USERS = 'create-users';
    const UPDATE_USERS = 'update-users';
    const DELETE_USERS = 'delete-users';
    const READ_GENERAL_STATS = 'read-general-stats';
    const READ_ROLES_PERMISSIONS = 'read-roles-permissions';
    const CREATE_ROLES = 'create-roles';
    const UPDATE_ROLES = 'update-roles';
    const DELETE_ROLES = 'delete-roles';
    const UPDATE_PERMISSIONS = 'update-permissions';
    const ACCESS_TELESCOPE = 'access-telescope';
    const ACCESS_API = 'access-api';
}
