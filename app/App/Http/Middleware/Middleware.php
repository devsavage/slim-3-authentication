<?php
namespace App\Http\Middleware;

use Interop\Container\ContainerInterface;

class Middleware
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function flash($type, $message)
    {
        $this->container->flash->addMessage($type, $message);
    }

    public function lang($key)
    {
        return $this->container->config->get("lang." . $key);
    }

    public function redirect($response, $path)
    {
        return $response->withRedirect($this->router()->pathFor($path));
    }

    protected function router()
    {
        return $this->container['router'];
    }
}