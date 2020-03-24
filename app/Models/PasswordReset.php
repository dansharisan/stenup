<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    const PASSWORD_RESET_TOKEN_TIME_VALIDITY_IN_MINUTE = 60;
    public $incrementing = false;
    protected $primaryKey = 'email';

    protected $fillable = [
        'email', 'token'
    ];
}
