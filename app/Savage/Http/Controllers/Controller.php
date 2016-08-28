<?php
namespace Savage\Http\Controllers;

/**
 * Base Controller class, all other controllers should extend this class.
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

    public function param($param)
    {
        return $this->request->getParam($param);
    }

    public function mail()
    {
        return $this->container->mail;
    }

    public function hash()
    {
        return new \Savage\Utils\Hash();
    }
}
