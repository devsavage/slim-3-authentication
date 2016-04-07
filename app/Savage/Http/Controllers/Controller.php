<?php

namespace Savage\Http\Controllers;

/**
 * Base Controller Class
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

    public function render($name, array $args = [])
    {
        return $this->container->view->render($this->response, $name . '.twig', $args);
    }

    public function redirect($path = null)
    {
        if($path) {
            return $this->response->withRedirect($this->router()->pathFor($path));
        }

        return $this->response->withRedirect($this->router()->pathFor('home'));
    }

    public function flash($type, $message)
    {
        return $this->container->flash->addMessage($type, $message);
    }

    public function flashNow($type, $message)
    {
        return $this->container->flash->addMessageNow($type, $message);
    }

    public function validator()
    {
        return new \Savage\Http\Validation\Validator($this->container);
    }

    public function router()
    {
        return $this->container->router;
    }

    public function user()
    {
        return $this->container->auth->user();
    }
}
