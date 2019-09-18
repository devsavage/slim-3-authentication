<?php

namespace App\Validation\Violin\Rules;

use App\Validation\Violin\Contracts\RuleContract;

class MinRule implements RuleContract
{
    public function run($value, $input, $args)
    {
        $number = isset($args[1]) && $args[1] === 'number';

        if ($number) {
            return (float) $value >= (float) $args[0];
        }

        return mb_strlen($value) >= (int) $args[0];
    }

    public function error()
    {
        return '{field} must be a minimum of {$0}.';
    }

    public function canSkip()
    {
        return true;
    }
}
