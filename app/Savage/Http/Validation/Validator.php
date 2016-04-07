<?php

namespace Savage\Http\Validation;

use Violin\Violin;
use Interop\Container\ContainerInterface;

class Validator extends Violin
{
    protected $user;

    public function __construct(ContainerInterface $container)
    {
        $this->user = $container->user;
    }
}
