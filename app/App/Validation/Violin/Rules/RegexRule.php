<?php

namespace App\Validation\Violin\Rules;

use App\Validation\Violin\Contracts\RuleContract;

class RegexRule implements RuleContract
{
    public function run($value, $input, $args)
    {
        return (bool) preg_match($args[0], $value);
    }

    public function error()
    {
        return '{field} was not in the correct format.';
    }

    public function canSkip()
    {
        return true;
    }
}
