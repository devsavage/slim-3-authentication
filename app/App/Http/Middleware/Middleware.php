<?php
namespace App\Http\Middleware;

use Interop\Container\ContainerInterface;
use Slim\Exception\NotFoundException;

class Middleware
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function auth()
    {
        return $this->container->auth;
    }

    public function user()
    {
        return $this->auth()->user();
    }

    public function flash($type, $message)
    {
        $this->container->flash->addMessage($type, $message);
    }

    public function config($key)
    {
        return $this->container->config->get($key);
    }

    public function lang($key)
    {
        return $this->config("lang." . $key);
    }

    public function redirect($response, $path)
    {
        return $response->withRedirect($this->router()->pathFor($path));
    }

    public function notFound($request, $response)
    {
        throw new NotFoundException($request, $response);
    }

    protected function router()
    {
        return $this->container['router'];
    }
}