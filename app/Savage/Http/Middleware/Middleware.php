<?php

namespace Savage\Http\Middleware;

use Interop\Container\ContainerInterface;

class Middleware
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}
