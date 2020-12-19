<?php
return [
    'settings' => [

        // Données globales d'application
        'chemin' => __DIR__,
        'appNom' => 'Covoit-CHU',
        'appli'=>'covoitchu',
        'url'=>'http://www.covoit-chu.picsat.fr',
        'chem'=>'/projets/covoitCHU',
        'version'=>'v1.0',
        'createur'=>'Picsat.fr',


        'displayErrorDetails' => false, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header
        'determineRouteBeforeAppMiddleware' => true,

        // View settings
        'view' => [
            'template_path' => __DIR__ . '/views',
            'twig' => [
                'cache' => __DIR__ . '/../cache/twig',
                'debug' => true,
                'auto_reload' => true
            ],
        ],

        // Monolog settings
        'logger' => [
            'name' => 'coivoit-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        // Config PROD DB
        'db' => [
                'driver' => 'mysql',
                'host' => 'picsatcovoitchu.mysql.db',
                'port'      => 3306,
                'database' => 'picsatcovoitchu',
                'username' => 'picsatcovoitchu',
                'password' => 'p1c5atc0v01tCHU',
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
        ],
    ],
];

