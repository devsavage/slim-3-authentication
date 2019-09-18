<?php

namespace App\Validation\Violin\Rules;

use App\Validation\Violin\Contracts\RuleContract;

class CheckedRule implements RuleContract
{
    public function run($value, $input, $args)
    {
        return in_array($value, ['yes', 'on', '1', 1, true, 'true'], true);
    }

    public function error()
    {
        return 'You need to check the {field} field.';
    }

    public function canSkip()
    {
        return true;
    }
}
