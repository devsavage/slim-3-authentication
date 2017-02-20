<?php
namespace App\Http\Controllers;

use App\Validation\Validator;
use Slim\Exception\NotFoundException;

/**
 * All controllers should extend this class.
 */

class Controller
{
    protected $request, $response, $args, $container;

    public function __construct($request, $response, $args, $container)
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;
        $this->container = $container;
    }

    public function __get($property)
    {
        if ($this->container->{$property}) {
            return $this->container->{$property};
        }
    }

    public function config($key)
    {
        return $this->config->get($key);
    }

    public function lang($key)
    {
        return $this->config("lang." . $key);
    }

    public function param($name)
    {
        return $this->request->getParam($name);
    }

    public function flash($type, $message)
    {
        return $this->flash->addMessage($type, $message);
    }

    public function flashNow($type, $message)
    {
        return $this->flash->addMessageNow($type, $message);
    }

    public function render($name, array $args = [])
    {
        return $this->container->view->render($this->response, $name . '.twig', $args);
    }

    public function redirect($path = null, $args = [], $params = [])
    {
        $path = $path != null ? $path : 'home';

        return $this->response->withRedirect($this->router()->pathFor($path, $args, $params));
    }

    public function notFound()
    {
        throw new NotFoundException($this->request, $this->response);
    }

    public function validator()
    {
        return new Validator($this->container);
    }

    public function auth()
    {
        return $this->auth;
    }

    public function user()
    {
        return $this->auth()->user();
    }

    protected function router()
    {
        return $this->container['router'];
    }
}