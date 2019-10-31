<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class DefaultRoleType extends Enum
{
    const Administrator = 'administrator';
    const Moderator = 'moderator';
    const Member = 'member';
}
