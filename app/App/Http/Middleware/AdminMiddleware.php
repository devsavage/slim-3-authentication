<?php
namespace App\Http\Middleware;

use App\Http\Middleware\Middleware;

class AdminMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if(!$this->container->auth->check() || $this->container->auth->check() && !$this->container->auth->user()->hasRole('admin')) {
            return $this->redirect($response, 'home');
        }
        
        $response = $next($request, $response);
        return $response;
    }
}
