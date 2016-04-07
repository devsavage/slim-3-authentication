<?php

namespace Savage\Http\Validation;

use Savage\Http\Auth\Models\User;

use Violin\Violin;

class Validator extends Violin
{
    public function __construct()
    {
        $this->addRuleMessages([
            'uniqueUsername' => 'That username is already in use.',
            'uniqueEmail' => 'That e-mail is already in use.',
        ]);
    }

    public function validate_uniqueUsername($value, $input, $args)
    {
        return !(bool) User::where('username', $value)->count();
    }

    public function validate_uniqueEmail($value, $input, $args)
    {
        return !(bool) User::where('username', $value)->count();
    }
}
