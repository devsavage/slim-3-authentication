<?php
namespace App\Http\Middleware;

use App\Http\Middleware\Middleware;
use App\Lib\Cookie;
use App\Lib\Session;

class RememberMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if(Cookie::exists(env('APP_REMEMBER_ID', 'APP_REMEMBER_TOKEN')) && !$this->container->auth->check()) {
            $data = Cookie::get(env('APP_REMEMBER_ID', 'APP_REMEMBER_TOKEN'));
            $credentials = explode('.', $data);

            if(empty(trim($data)) || count($credentials) !== 2) {
                Cookie::destroy(env('APP_REMEMBER_ID', 'APP_REMEMBER_TOKEN'));
                return $this->redirect($response, 'home');
            } else {
                $identifier = $credentials[0];
                $token = $this->container->hash->hash($credentials[1]);

                $user = $this->container->auth->where('remember_identifier', $identifier)->first();

                if($user) {
                    if($this->container->hash->verifyHash($token, $user->remember_token)) {
                        if($user->active) {
                            Session::set(env('APP_AUTH_ID', 'user_id'), $user->id);
                            // We must define a reponse with a redirect to detect a session when we first hit the page.
                            $response = $response->withRedirect($this->container->config->get('site.url') . $_SERVER['REQUEST_URI']);
                            return $next($request, $response);
                        } else {
                            Cookie::destroy(env('APP_REMEMBER_ID', 'APP_REMEMBER_TOKEN'));
                            
                            $user->removeRememberCredentials();

                            $this->flash("warning", "Your account has not been activated.");
                            return $this->redirect($response, 'auth.login');
                        }
                    } else {
                        $user->removeRememberCredentials();
                    }
                }
            }
        }
        
        $response = $next($request, $response);
        return $response;
    }
}
