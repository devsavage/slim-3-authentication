<?php
namespace App\Http\Middleware;

use App\Http\Middleware\Middleware;

class AdminMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if($this->auth()->check()) {
            if($this->user()->isAdmin() || $this->user()->isSuperAdmin() || $this->user()->can('view admin pages')) {
                $response = $next($request, $response);
                return $response;
            }
        }

        return $this->notFound($request, $response);
    }
}
