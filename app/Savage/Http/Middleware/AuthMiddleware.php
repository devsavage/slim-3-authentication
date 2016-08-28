<?php
namespace Savage\Http\Middleware;

/**
 * AuthMiddleware is used for routes that require a user to be logged in.
 */

class AuthMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if (!$this->container->auth->check()) {
            $this->container->flash->addMessage('info', 'Please sign in before doing that.');
            return $response->withRedirect($this->container->router->pathFor('auth.login'));
        }

        if($this->container->auth->check() && !$this->container->auth->user()->active) {
            $this->container->flash->addMessage('error', 'Your account has not been activated, yet. Please check your e-mail for an activation link.');
            $this->container->auth->logout();
            return $response->withRedirect($this->container->router->pathFor('auth.login'));
        }

        $response = $next($request, $response);
        return $response;
    }
}
