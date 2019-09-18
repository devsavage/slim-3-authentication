<?php

namespace App\Validation\Violin\Contracts;

use Closure;

interface ValidatorContract
{
    public function validate(array $input, $rules = []);
    public function passes();
    public function fails();
    public function errors();
    public function addRuleMessage($rule, $message);
    public function addRuleMessages(array $messages);
    public function addFieldMessage($field, $rule, $message);
    public function addFieldMessages(array $messages);
    public function addRule($name, Closure $callback);
}
