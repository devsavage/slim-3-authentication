<?php
namespace App\Http\Middleware;

use App\Http\Middleware\Middleware;

class GuestMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if($this->auth()->check()) {
            return $this->redirect($response, 'home');
        }
        
        $response = $next($request, $response);
        return $response;
    }
}
