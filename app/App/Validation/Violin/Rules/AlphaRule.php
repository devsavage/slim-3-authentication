<?php

namespace App\Validation\Violin\Rules;

use App\Validation\Violin\Contracts\RuleContract;

class AlphaRule implements RuleContract
{
    public function run($value, $input, $args)
    {
        return (bool) preg_match('/^[\pL\pM]+$/u', $value);
    }

    public function error()
    {
        return '{field} must be alphabetic.';
    }

    public function canSkip()
    {
        return true;
    }
}
