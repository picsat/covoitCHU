<?php

namespace App\Controllers\Calendar;

use App\Models\User;
use App\Calendar\Calendar;
use App\Auth\Auth;
use App\Controllers\Controller;


/**
 * AuthController
 *
 * @author    Haven Shen <havenshen@gmail.com>
 * @copyright    Copyright (c) Haven Shen
 */
class CalendarController extends Controller
{



     public function getcontent($request, $response, array $args)
    {
        //$user = json_decode($this->auth->user(), true);


        // on recupere le user de la session en cours
        $user = $this->auth->user();

        if (! $user) {
            return false;
        }

        //$user = User::find(isset($_SESSION['user']) ? $_SESSION['user'] : 0);


        $user->setPrenom($user->last_update);
        $user->setNom($user->nom);
        $user->setEmail($user->email);

        $data['user'] = [
                            'nom' => $user->nom,
                            'prenom' => $user->prenom,
                            'email' => $user->email,
                            'fullName' => $user->getFullNom(),
                        ];

        return $this->view->render($response,'calendrier.twig', $data);

    }
}
