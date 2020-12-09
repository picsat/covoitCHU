<?php
use App\Calendar\Calendar;
use App\Middleware\GuestMiddleware;
use App\Middleware\AuthMiddleware;


$container = $app->getContainer();

$app->group('', function () use ($app){

        $this->post('/calendrier', 'CalendarController:getcontent')->setName('calendar.general');
        $this->post('/calendrier/update', 'CalendarController:update')->setName('calendar.update');

})->add(new AuthMiddleware($container));

