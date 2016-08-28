<?php
namespace Savage\Http;

use Slim\Csrf\Guard as CSRF;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Gaurd extends Slim\Csrf\Gaurd to add the ability to exclude routes from CSRF protection.
 */

class Guard extends CSRF
{
    public $excluded = [];

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $routeName = $request->getRequestTarget();

        // Validate POST, PUT, DELETE, PATCH requests
        if (!in_array($routeName, $this->excluded) && in_array($request->getMethod(), ['POST', 'PUT', 'DELETE', 'PATCH'])) {
            $body = $request->getParsedBody();
            $body = $body ? (array)$body : [];
            $name = isset($body[$this->prefix . '_name']) ? $body[$this->prefix . '_name'] : false;
            $value = isset($body[$this->prefix . '_value']) ? $body[$this->prefix . '_value'] : false;
            if (!$name || !$value || !$this->validateToken($name, $value)) {
                // Need to regenerate a new token, as the validateToken removed the current one.
                $request = $this->generateNewToken($request);

                $failureCallable = $this->getFailureCallable();
                return $failureCallable($request, $response, $next);
            }
        }
        // Generate new CSRF token
        $request = $this->generateNewToken($request);

        // Enforce the storage limit
        $this->enforceStorageLimit();

        return $next($request, $response);
    }

    public function setExcludedRoutes(array $routes = []) {
        $this->excluded = $routes;
        return $this;
    }
}
