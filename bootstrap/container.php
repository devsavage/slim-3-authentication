<?php
return [
    'settings' => [
        'displayErrorDetails' => getenv('APP_ENV') === "production" ? false : true,
        'determineRouteBeforeAppMiddleware' => true,
        'viewTemplatesDirectory' => INC_ROOT . '/../resources/views',
    ],

    'auth' => function($c) {
        return new \App\Auth\Auth;
    },

    'hash' => function($c) {
        return new \App\Lib\Hash;
    },

    'flash' => function($c) {
        return new \App\Lib\Flash;
    },

    'view' => function($c) {
        $view = new \Slim\Views\Twig($c['settings']['viewTemplatesDirectory'], [
            'debug' => getenv('APP_ENV') === "production" ? false : true
        ]);

        $view->getEnvironment()->addGlobal('auth', [
            'check' => $c->auth->check(),
            'user' => $c->auth->user(),
        ]);

        $view->addExtension(new \Slim\Views\TwigExtension($c['router'], $c['request']->getUri()));
        $view->addExtension(new \Twig_Extension_Debug);
        $view->addExtension(new \App\Twig\TwigExtension);

        $view->getEnvironment()->addGlobal('flash', $c['flash']);

        return $view;
    },

    'csrf' => function($c) {
        $guard = new \Slim\Csrf\Guard;

        $guard->setFailureCallable(function($request, $response, $next) use ($c) {
            $request = $request->withAttribute("csrf_status", false);
            if($request->getAttribute('csrf_status') === false) {
                $c['flash']->addMessage('error', "CSRF verification failed, terminating your request.");

                return $response->withStatus(400)->withRedirect($c['router']->pathFor('home'));
            } else {
                return $next($request, $response);
            }
        });

        return $guard;
    },

    'db' => function($c) {
        $capsule = new \Illuminate\Database\Capsule\Manager;

        $capsule->addConnection($c['config']->get('database'), 'default');

        return $capsule;
    },
];