<?php
return [
    'settings' => [
        'displayErrorDetails' => getenv('APP_ENV') === "production" ? false : true,
        'determineRouteBeforeAppMiddleware' => true,
        'viewTemplatesDirectory' => INC_ROOT . '/../resources/views',
    ],

    'user' => function($c) {
        return new \App\Database\User;
    },

    'auth' => function($c) {
        return new \App\Auth\Auth;
    },

    'hash' => function($c) {
        return new \App\Lib\Hash;
    },

    'flash' => function($c) {
        return new \App\Lib\Flash;
    },

    'recaptcha' => function($c) {
        return new \ReCaptcha\ReCaptcha($c->config->get('plugins.recaptcha.secret'));
    },

    'twig' => function($c) {
        $twig = new \Twig_Environment(new \Twig_Loader_Filesystem($c['settings']['viewTemplatesDirectory']));

        // We need to load this again to use our functions with our mailing system.
        $twig->addExtension(new \App\Twig\TwigExtension($c));

        return $twig;
    },

    'view' => function($c) {
        $view = new \Slim\Views\Twig($c['settings']['viewTemplatesDirectory'], [
            'debug' => env('APP_ENV', 'development') === "production" ? false : true
        ]);

        $view->getEnvironment()->addGlobal('auth', [
            'check' => $c->auth->check(),
            'user' => $c->auth->user(),
        ]);

        $view->addExtension(new \Slim\Views\TwigExtension($c['router'], $c['request']->getUri()));
        $view->addExtension(new \Twig_Extension_Debug);
        $view->addExtension(new \App\Twig\TwigExtension($c));

        $view->getEnvironment()->addGlobal('flash', $c['flash']);

        return $view;
    },

    'notFoundHandler' => function($c) {
        return function($request, $response) use ($c) {
            $response = $response->withStatus(404);
            return $c->view->render($response, 'errors/404.twig', [
                'request_uri' => urldecode($_SERVER['REQUEST_URI'])
            ]);
        };
    },

    'notAllowedHandler' => function($c) {
        return function ($request, $response, $methods) use ($c) {
            $response = $response->withStatus(405);
            return $c->view->render($response, 'errors/405.twig', [
                'request_uri' => $_SERVER['REQUEST_URI'],
                'method' => $_SERVER['REQUEST_METHOD'],
                'methods' => implode(', ', $methods)
            ]);
        };
    },

    'errorHandler' => function($c) {
        return function($request, $response, $exception) use ($c) {
            $response = $response->withStatus(500);

            $data = [
                'exception' => null
            ];

            if(env('APP_ENV') === "development") {
                $data['exception'] = $exception->getMessage();
            }

            return $c->view->render($response, 'errors/500.twig', $data);
        };
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

    'mail' => function($c) {
        $mailer = new \PHPMailer;

        $mailer->isSMTP();
        $mailer->Host = $c['config']->get('mail.host');
        $mailer->Port = $c['config']->get('mail.port');
        $mailer->Username = $c['config']->get('mail.username');
        $mailer->Password = $c['config']->get('mail.password');
        $mailer->SMTPAuth = true;
        $mailer->SMTPSecure = false;
        $mailer->FromName = $c['config']->get('mail.from.name');
        $mailer->From = $c['config']->get('mail.from');

        $mailer->isHTML(true);

        return new \App\Mail\Mailer($mailer, $c);
    },
];