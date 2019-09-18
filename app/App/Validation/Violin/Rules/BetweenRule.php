<?php

namespace App\Validation\Violin\Rules;

use App\Validation\Violin\Contracts\RuleContract;

class BetweenRule implements RuleContract
{
    public function run($value, $input, $args)
    {
        return ($value >= $args[0] && $value <= $args[1]) ? true : false;
    }

    public function error()
    {
        return '{field} must be between {$0} and {$1}.';
    }

    public function canSkip()
    {
        return true;
    }
}
