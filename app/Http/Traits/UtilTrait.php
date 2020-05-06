<?php

namespace App\Http\Traits;

trait UtilTrait {
    /*
    * Generate a random string combined with digit and alphabetical characters
    **/
    protected function quickRandom($length = 16)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }
}
