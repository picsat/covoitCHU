<?php
use App\Calendar\Calendar;
use App\Middleware\GuestMiddleware;
use App\Middleware\AuthMiddleware;


$container = $app->getContainer();

$app->group('', function () {

        $this->get('/calendrier', 'CalendarController:getcontent')->setName('calendar.general');
        $this->post('/calendrier/update', 'CalendarController:postEvents')->setName('calendar.postEvents');

})->add(new AuthMiddleware($container));

