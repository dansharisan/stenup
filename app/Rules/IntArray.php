<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class IntArray implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return is_array($value) && ($value == array_filter($value, 'is_numeric'));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.int_array_string');
    }
}
