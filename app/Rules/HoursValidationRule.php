<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class HoursValidationRule implements Rule
{
    public const REGEX_PATTERN = "/^[0-9]{1,2}(\.(25|50|5|75|00|0))?$/";
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
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
        return preg_match(self::REGEX_PATTERN, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The hours field format is not correct.';
    }
}
