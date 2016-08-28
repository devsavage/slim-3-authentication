<?php
namespace Savage\Http\Middleware;

use Interop\Container\ContainerInterface;

/**
 * Middleware is our base middleware class. This will handle functionality across all classes that extends this.
 */

class Middleware
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}
