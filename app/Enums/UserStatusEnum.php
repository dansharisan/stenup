<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class UserStatusEnum extends Enum
{
    const Inactive = 0;
    const Active = 1; // Only user with Active status can be logged in
    const Banned = 2;
}
