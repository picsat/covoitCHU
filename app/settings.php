<?php
return [
    'settings' => [

        // Données globales d'application
        'chemin' => __DIR__,
        'appNom' => 'covoit-CHU',
        'appli'=>'covoitchu',
        'url'=>'http://covoit-chu',
        'chem'=>'/projets/www.covoit-chu.picsat.fr',
        'version'=>'v0.1',
        'createur'=>'Picsat.fr',



        'displayErrorDetails' => false, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header
        'determineRouteBeforeAppMiddleware' => true,

        // View settings
        'view' => [
            'template_path' => __DIR__ . '/views',
            'twig' => [
                'cache' => __DIR__ . '/../cache/twig', //false,
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

        // Config ELLOQUENT DB
         'db' => [
                'driver' => 'mysql',
                'host' => 'picsatcovoitchu.mysql.db',
                'port'      => 3306,
                'database' => 'picsatcovoitchu',
                'username' => 'picsatcovoitchu',
                'password' => '*******',
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
            ]
    ],
];
