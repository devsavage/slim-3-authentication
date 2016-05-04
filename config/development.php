<?php

return [
    'settings' => [
        'determineRouteBeforeAppMiddleware' => true,
        'baseUrl' => 'http://127.0.0.1:9999/',
        'displayErrorDetails' => true,
        'viewTemplateDirectory' => '../resources/views',
        'auth' => [
            'session_key' => 'user_id'
        ],
        'db' => [
            'driver' => 'mysql',
            'host' => '127.0.0.1',
            'username' => 'dev',
            'password' => 'dev',
            'database' => 'auth',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci'
        ],

        'excludedRoutes' => []
    ],

    'db' => function($container) {
        $capsule = new \Illuminate\Database\Capsule\Manager;

        $capsule->addConnection($container['settings']['db']);
        $capsule->setAsGlobal();

        return $capsule;
    },

    'view' => function($container) {
        $view = new \Slim\Views\Twig($container['settings']['viewTemplateDirectory'], [
            'debug' => true
        ]);

        $view->addExtension(new \Slim\Views\TwigExtension($container['router'], $container['request']->getUri()));
        $view->addExtension(new \Savage\Twig\TwigExtension($container));
        $view->getEnvironment()->addGlobal('flash', $container->flash);
        $view->getEnvironment()->addGlobal('auth', [
            'check' => $container->auth->check(),
            'user' => $container->auth->user(),
        ]);

        return $view;
    },

    'flash' => function($container) {
        return new \Savage\Utils\Flash;
    },

    'auth' => function($container) {
        return new \Savage\Http\Auth\Auth;
    },

    'csrf' => function($container) {
        $gaurd = new \Savage\Http\Guard;
        $gaurd->setExcludedRoutes($container['settings']['excludedRoutes']);

        $gaurd->setFailureCallable(function ($request, $response, $next) use ($container){
            $request = $request->withAttribute("csrf_status", false);
            if ($request->getAttribute('csrf_status') === false) {
                $container->flash->addMessage('error', 'CSRF verification failed. Terminating your request.');

                return $response->withStatus(400)->withRedirect($container['router']->pathFor('home'));
            } else {
                return $next($request, $response);
            }
        });

        return $gaurd;
    },

    'errorHandler' => function($container) {
        return function($request, $response, $exception) use ($container) {
            $response = $response->withStatus(500);
            return $container->view->render($response, 'errors/500.twig');
        };
    },

    'notFoundHandler' => function($container) {
        return function($request, $response) use ($container) {
            $response = $response->withStatus(404);
            return $container->view->render($response, 'errors/404.twig', [
                'request_uri' => urldecode($_SERVER['REQUEST_URI'])
            ]);
        };
    },

    'notAllowedHandler' => function($container) {
        return function ($request, $response, $methods) use ($container) {
            $response = $response->withStatus(405);
            return $container->view->render($response, 'errors/405.twig', [
                'request_uri' => $_SERVER['REQUEST_URI'],
                'method' => $_SERVER['REQUEST_METHOD'],
                'methods' => implode(', ', $methods)
            ]);
        };
    },
];
