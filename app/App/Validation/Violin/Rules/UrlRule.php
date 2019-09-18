<?php

namespace App\Validation\Violin\Rules;

use App\Validation\Violin\Contracts\RuleContract;

class UrlRule implements RuleContract
{
    public function run($value, $input, $args)
    {
        return filter_var($value, FILTER_VALIDATE_URL) !== false;
    }

    public function error()
    {
        return '{field} must be a valid URL.';
    }

    public function canSkip()
    {
        return true;
    }
}
