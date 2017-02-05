<?php
namespace App;

use Slim\App as Slim;

class App extends Slim
{
    /**
     * Customized route mapping for a cleaner workflow.
     */
    public function route(array $methods, $uri, $controller, $func = null)
    {
        if($func) {
            return $this->map($methods, $uri, function($request, $response, $args) use ($methods, $uri, $controller, $func) {
                $callable = new $controller($request, $response, $args, $this);
                return call_user_func_array([$callable, $request->getMethod() . ucfirst($func)], $args);
            });
        }

        return $this->map($methods, $uri, function($request, $response, $args) use ($controller, $uri) {
            $callable = new $controller($request, $response, $args, $this);
            return call_user_func_array([$callable, $request->getMethod()], $args);
        });
    }
}