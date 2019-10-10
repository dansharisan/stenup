<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PermissionType extends Enum
{
  const VIEW_USERS = 'view-users';
  const CREATE_USERS = 'create-users';
  const UPDATE_USERS = 'update-users';
  const DELETE_USERS = 'delete-users';
}
