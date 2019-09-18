<?php

namespace App\Validation\Violin\Rules;

use App\Validation\Violin\Contracts\RuleContract;

class DateRule implements RuleContract
{
    public function run($value, $input, $args)
    {
        if ($value instanceof DateTime) {
            return true;
        }

        if (strtotime($value) === false) {
            return false;
        }

        $date = date_parse($value);

        return checkdate($date['month'], $date['day'], $date['year']);
    }

    public function error()
    {
        return '{field} must be a valid date.';
    }

    public function canSkip()
    {
        return true;
    }
}
