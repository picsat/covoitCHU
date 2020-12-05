<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;


require __DIR__ . '/../app/routes/users.php';
require __DIR__ . '/../app/routes/auth.php';
require __DIR__ . '/../app/routes/calendar.php';


return function (App $app) {



    $container = $app->getContainer();


    /*$app->get('/', function (Request $request, Response $response, array $args) use ($container)  {

        $container->get('logger')->info("'/' route");

       return $this->view->render($response, 'home.twig', [
            'content' =>  $args['content']
       ]);
    });*/

    $app->get('/', 'HomeController:index')->setName('home');



};



// Run app
$app->run();
