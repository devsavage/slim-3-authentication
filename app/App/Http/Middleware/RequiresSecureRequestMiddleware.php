<?php
namespace App\Http\Middleware;

use App\Http\Middleware\Middleware;

class RequiresSecureRequestMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if(env("APP_ENV") == "production" && !$this->isSecure()) {
            throw new \RuntimeException(sprintf("Insecure requests are not allowed for %s!", $request->getRequestTarget()));
        }

        $response = $next($request, $response);
        return $response;
    }
}
