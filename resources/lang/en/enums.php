<?php

use App\Enums\ErrorEnum;

return [
    ErrorEnum::class => [
        ErrorEnum::AUTH0001 => "Either wrong combination of email and password or the account is inactive/banned/deleted",
        ErrorEnum::AUTH0002 => "Incorrect or expired activation token",
        ErrorEnum::AUTH0003 => "Couldn't find the user with that e-mail address",
        ErrorEnum::AUTH0004 => "Invalid password reset token",
        ErrorEnum::AUTH0005 => "Expired password reset token",
        ErrorEnum::AUTH0006 => "Invalid email and/or token",
        ErrorEnum::AUTH0007 => "Invalid access token",
        ErrorEnum::AUTH0008 => "Expired access token",
        ErrorEnum::AUTH0009 => "Blacklisted access token",
        ErrorEnum::AUTH0010 => "Unauthorized request",
        ErrorEnum::AUTH0011 => "Email is not yet verified",
        ErrorEnum::AUTH0012 => "Invalid role ID",
        ErrorEnum::AUTH0013 => "Invalid roles-permissions matrix",
        ErrorEnum::USER0001 => "Invalid user ID",
        ErrorEnum::USER0002 => "Invalid user ID string sequence",
        ErrorEnum::USER0003 => "Invalid or no role was selected",
        ErrorEnum::GENR0001 => "Server error",
        ErrorEnum::GENR0002 => "Invalid input data",
    ],
];
