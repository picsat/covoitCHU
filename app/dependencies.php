<?php

use Slim\App;

return function (App $app) {
    $container = $app->getContainer();

    // ORM ELLOQUENT Factory
    $container['db'] = function ($container) {
        $capsule = new \Illuminate\Database\Capsule\Manager;
        $capsule->addConnection($container['settings']['db']);

        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        return $capsule;
    };

    $container['auth'] = function($container) {
        return new \App\Auth\Auth;
    };

    $container['flash'] = function($container) {
        return new \Slim\Flash\Messages;
    };

    // view renderer
    $container['renderer'] = function ($c) {
        $settings = $c->get('settings')['renderer'];
        return new \Slim\Views\PhpRenderer($settings['template_path']);
    };

    // monolog
    $container['logger'] = function ($c) {
        $settings = $c->get('settings')['logger'];
        $logger = new \Monolog\Logger($settings['name']);
        $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
        $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
        return $logger;
    };




    /* TWIG */
    $container['view'] = function ($c) {
        $settings = $c->get('settings');
        $view = new \Slim\Views\Twig($settings['view']['template_path'], $settings['view']['twig']);

        // Ajout d'extensions
        $view->addExtension(new Slim\Views\TwigExtension($c->get('router'), $c->get('request')->getUri()));
        $view->addExtension(new Twig_Extension_Debug());


        // Variables Twig globales
        $twig = $view->getEnvironment();
        $twig->addGlobal('application', [
                            'appId' => $settings['appli'],
                            'name' => $settings['appNom'],
                            'version' => $settings['version'],
                            'author' => $settings['createur'],
                        ]);
        $twig->addGlobal('auth', [
                            'check' => $c->auth->check(),
                            'user' => $c->auth->user()
                        ]);
        $twig->addGlobal('flash', $c->flash);

        return $view;
    };


    // --------------------
    // Service factories
    // --------------------

    $container['validator'] = function ($container) {
        return new \App\Validation\Validator;
    };

    $container['HomeController'] = function($container) {
        return new \App\Controllers\HomeController($container);
    };

    $container['AuthController'] = function($container) {
        return new \App\Controllers\Auth\AuthController($container);
    };

    $container['PasswordController'] = function($container) {
        return new \App\Controllers\Auth\PasswordController($container);
    };

    $container['csrf'] = function($container) {
        $guard= new \Slim\Csrf\Guard;
        $guard->setPersistentTokenMode(true);
        return $guard;
    };

    $container['CalendarController'] = function($container) {
        return new \App\Controllers\Calendar\CalendarController($container);
    };



/// .............................??????????????????????????????????????????
    $container['UserController'] = function ($c) {

        $view = new \App\Controllers\UserController($c->get('view'), $c->get('logger'), $c->get('settings'));
        return $view ;
    };





    // -----------------------------------------------------------------------------
    // App factory
    // -----------------------------------------------------------------------------
    /*
    $container['App\Fiche\FicheController'] = function ($c) {
        return new App\Fiche\FicheController($c->get('view'), $c->get('logger'), $c->get('pdo'));
        };

    // -----------------------------------------------------------------------------
    // Générique factory
    // -----------------------------------------------------------------------------
    $container['App\Generique\Librairies'] = function ($c) {
        return new App\Generique\Librairies($c->get('view'), $c->get('logger'), $c->get('settings'), $c->get('pdo'));
    };
    */
};


