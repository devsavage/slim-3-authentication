<?php

namespace App\Validation\Violin\Rules;

use App\Validation\Violin\Contracts\RuleContract;

class RequiredRule implements RuleContract
{
    public function run($value, $input, $args)
    {
        $value = preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $value);

        return !empty($value);
    }

    public function error()
    {
        return '{field} is required.';
    }

    public function canSkip()
    {
        return false;
    }
}
