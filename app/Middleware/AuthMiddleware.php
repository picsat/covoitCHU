<?php

namespace App\Middleware;

/**
 * AuthMiddleware
 *
 * @author    Haven Shen <havenshen@gmail.com>
 * @copyright    Copyright (c) Haven Shen
 */
class AuthMiddleware extends Middleware
{

    public function __invoke($request, $response, $next)
    {
        if(! $this->container->auth->check()) {
            $this->container->flash->addMessage('error', 'Please sign in before doing that');
            return $response->withRedirect($this->container->router->pathFor('auth.signin'));
        }

        /**
         * Recupere le user de la session en cours
         * -> Pour donner le user aux controllers qui en auront besoin si la route est protégée par ce Middleware
         */
        //$user = new \App\Auth\Auth;
        //$user = $this->container->auth->user();

        //$user->getPrenom($user->prenom)."éé";
        //$user->setNom($user->nom);
        //$user->setEmail($user->email);
        //$user->setPassword(password_hash($user->password, PASSWORD_DEFAULT));
        //$user->setPassword($user->password);
        //$this->container->user = $user;

        $response = $next($request, $response);
        return $response;
    }
}
