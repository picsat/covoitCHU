<?php

use Slim\App;
use Respect\Validation\Validator as v;

return function (App $app) {

    // Réglage dates France
    date_default_timezone_set('Europe/Paris');
    setlocale (LC_TIME, 'fr_FR.utf8','fra');

    $container = $app->getContainer();

    $app->add(new \App\Middleware\ValidationErrorsMiddleware($container));
    $app->add(new \App\Middleware\OldInputMiddleware($container));
    $app->add(new \App\Middleware\CsrfViewMiddleware($container));

    $app->add($container->csrf);


    v::with('App\\Validation\\Rules\\');



};
