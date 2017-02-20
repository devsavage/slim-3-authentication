<?php
namespace App\Http\Middleware;

use App\Http\Middleware\Middleware;

class AuthMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if(!$this->auth()->check()) {
            $this->flash('warning', $this->lang('alerts.requires_auth'));
            return $this->redirect($response, 'auth.login');
        }
        
        $response = $next($request, $response);
        return $response;
    }
}
