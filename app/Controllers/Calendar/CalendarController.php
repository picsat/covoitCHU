<?php

namespace App\Controllers\Calendar;

use App\Models\User;
use App\Models\Event;
use App\Calendar\Calendar;
use App\Auth\Auth;
use App\Controllers\Controller;
use Illuminate\Database\Capsule\Manager as DB;


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

        $userEvents = array();

        // soit on part du principe que c'est a partir des Events du user en cours qu'il faut aller chercher les relations (User / type_event...)
        // renvoi un tableau d'Event
        $userEvents = \App\Models\Event::with('user')->where('id_user','=',$user->id)->get();

        foreach ($userEvents as $event) {
            $sj = date("d-m-Y", strtotime($event->date));
            //$sj = $dateT->date_format('d-M-Y');
            $data['user']['events'][$event->id] =  json_decode($event, true);
            $data['user']['events'][$event->id]['translated_date'] = $sj;


            $translated_eventType=  json_decode($event->getEventType(), true);

            $data['user']['events'][$event->id]['translated_eventType'] = $translated_eventType[0]['titre_event'];

        }

        // soit on part du principe que c'est a partir du User en cours pour aller chercher les relations ses Events (User / type_event...)
        // renvoi un tableau d'Event qu'on inmplemente a data.user.events
        /*
        $userEvents = $user->events;


        foreach ($userEvents as $userEvent) {
            $sj = date("d-m-Y", strtotime($event->date));
            //$sj = $dateT->date_format('d-M-Y');
            $data['user']['events'][$userEvent->id] =  json_decode($userEvent, true);
            $data['user']['events'][$userEvent->id]['translated_date'] = $sj;
            $data['user']['events'][$userEvent->id]['translated_eventType'] = $userEvent->titre_event;

        }*/


        return $this->view->render($response,'calendrier.twig', $data);

    }
}
