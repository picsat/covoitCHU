<?php
use App\Auth\Auth;
use App\Middleware\GuestMiddleware;
use App\Middleware\AuthMiddleware;

 $container = $app->getContainer();

  $app->group('', function () use ($app) {
        $this->get('/auth/signup', 'AuthController:getSignUp')->setName('auth.signup');
        $this->post('/auth/signup', 'AuthController:postSignUp');

        $this->get('/auth/signin', 'AuthController:getSignIn')->setName('auth.signin');
        $this->post('/auth/signin', 'AuthController:postSignIn');
    })->add(new GuestMiddleware($container));

    $app->group('', function () {
        $this->get('/auth/signout', 'AuthController:getSignOut')->setName('auth.signout');
        $this->get('/auth/password/change', 'PasswordController:getChangePassword')->setName('auth.password.change');
        $this->post('/auth/password/change', 'PasswordController:postChangePassword');
    })->add(new AuthMiddleware($container));

