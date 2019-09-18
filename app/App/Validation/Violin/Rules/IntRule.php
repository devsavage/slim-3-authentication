<?php

namespace App\Validation\Violin\Rules;

use App\Validation\Violin\Contracts\RuleContract;

class IntRule implements RuleContract
{
    public function run($value, $input, $args)
    {
        return is_numeric($value) && (int)$value == $value;
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
