<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class DefaultRoleType extends Enum
{
    const ADMINISTRATOR = 'administrator'; // Highest role should be the first role
    const MODERATOR = 'moderator';
    const MEMBER = 'member';
}
