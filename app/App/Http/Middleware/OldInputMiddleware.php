<?php
namespace App\Http\Middleware;

use App\Http\Middleware\Middleware;
use App\Lib\Session;

class OldInputMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        Session::set('old', $request->getParams());
        $this->container->view->getEnvironment()->addGlobal('old', Session::get('old'));
        
        $response = $next($request, $response);
        return $response;
    }
}
