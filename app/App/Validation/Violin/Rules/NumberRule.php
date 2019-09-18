<?php

namespace App\Validation\Violin\Rules;

use App\Validation\Violin\Contracts\RuleContract;

class NumberRule implements RuleContract
{
    public function run($value, $input, $args)
    {
        return is_numeric($value);
    }

    public function error()
    {
        return '{field} must be a number.';
    }

    public function canSkip()
    {
        return true;
    }
}
