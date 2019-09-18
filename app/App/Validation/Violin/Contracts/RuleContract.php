<?php

namespace App\Validation\Violin\Contracts;

interface RuleContract
{
    /**
     * Runs the rule to check validity. Returning false fails
     * the check and returning true passes the check.
     *
     * @param  mixed $value
     * @param  array $input
     * @param  array $args
     *
     * @return bool
     */
    public function run($value, $input, $args);

    /**
     * The error given if the rule fails.
     *
     * @return string
     */
    public function error();

    /**
     * If the rule can be skipped, if the value given
     * to the validator is not required.
     *
     * @return [type] [description]
     */
    public function canSkip();
}
