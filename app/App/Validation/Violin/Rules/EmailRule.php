<?php

namespace App\Validation\Violin\Rules;

use App\Validation\Violin\Contracts\RuleContract;

class EmailRule implements RuleContract
{
    public function run($value, $input, $args)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function error()
    {
        return '{field} must be a valid email address.';
    }

    public function canSkip()
    {
        return true;
    }
}
