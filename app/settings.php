<?php
return [
    'settings' => [

        // Données globales d'application
        'chemin' => __DIR__,
        'appNom' => 'covoit-CHU',
        'appli'=>'covoitchu',
        'url'=>'http://covoitchu',
        'chem'=>'/projets/www.covoitchu.picsat.fr',
        'version'=>'v0.1',
        'createur'=>'Picsat.fr',



        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // View settings
        'view' => [
            'template_path' => __DIR__ . '/views',
            'twig' => [
                'cache' => false, //__DIR__ . '/../cache/twig',
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
                'host' => 'localhost',
                'port'      => 3306,
                'database' => 'cvoit',
                'username' => 'root',
                'password' => '5h1n0b1',
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
            ]
    ],
];
