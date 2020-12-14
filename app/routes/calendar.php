<?php
use App\Calendar\Calendar;
use App\Middleware\GuestMiddleware;
use App\Middleware\AuthMiddleware;


$container = $app->getContainer();

$app->group('', function () use ($app){

        $this->get('/calendrier[/{month}-{year}]', 'CalendarController:getcontent')->setName('calendar.general');
        $this->post('/calendrier', 'CalendarController:getcontent')->setName('calendar.general');
        $this->post('/calendrier/update', 'CalendarController:update')->setName('calendar.update');

        $this->get('/calendrier/all[/{month}-{year}]', 'CalendarController:getcontentForAll')->setName('calendar.forall');
        $this->post('/calendrier/all', 'CalendarController:getcontentForAll')->setName('calendar.forall');

})->add(new AuthMiddleware($container));

