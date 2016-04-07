<?php

return [
    'settings' => [
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
        ]
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
];
