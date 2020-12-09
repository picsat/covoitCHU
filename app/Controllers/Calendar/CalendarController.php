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

        //recup des détes passée en POST
        $postArr = $request->getParsedBody();
        $month = $postArr['selMois'];
        $year = $postArr['selAnnee'];

        $calendrier = new Calendar($month, $year);
        /*$calendrier = $calendar->makeCalendar($month, $year);
        $calendrier["month_literal"] = $calendar->MonthToString($month);*/
        /*$data["calendar"] = [
                                "month" => $calenda,
                                "year" => $year,
                            ];*/





        //$data["calendar"]["calendaro"] = $calendar->makeCalendar($month, $year);

        //return '<pre>' .var_dump($this). '</pre>';


        // on recupere le user de la session en cours
        $user = $this->auth->user();

        if (! $user) {
            return false;
        }



        //$user = User::find(isset($_SESSION['user']) ? $_SESSION['user'] : 0);


        $user->setPrenom($user->prenom);
        $user->setNom($user->nom);
        $user->setEmail($user->email);

        /*$data['user'] = [
                            'nom' => $user->nom,
                            'prenom' => $user->prenom,
                            'email' => $user->email,
                            'fullName' => $user->getFullNom(),
                        ];
        */
        $user_events = array();

        // soit on part du principe que c'est a partir des Events du user en cours qu'il faut aller chercher les relations (User / type_event...)
        // renvoi un tableau d'Event

        /**
         * Recupération des event du mois du calendrier en cours pour le user indentifié
         * renvoie un tableau d'event contenant les events et les info users si besoin
         */

        $firstDayMonth = $calendrier->getFirstDay()->format('Y-m-d H:i:s');
        $lastDayMonth = $calendrier->getLastDay()->format('Y-m-d H:i:s');



        $userEvents = \App\Models\Event::with('user')->where('id_user','=',$user->id)->whereBetween('date', [$firstDayMonth, $lastDayMonth])->get();
        var_dump($userEvents);

        $liste = new \App\Models\Event;
        $EventTypes = json_decode($liste->getAllEventType());

        foreach ($EventTypes as $type) {

             $liste_type_event[$type->id] = [
                                                "titre"=> $type->titre,
                                                "description"=> $type->description,
                                             ];

        }


        foreach ($userEvents as $event) {
            $sj = date("Ymd", strtotime($event->date));


            $user_events[$sj] =  [
                                    "id" => $event->id,
                                    "date" => $event->date,
                                    "translated_date" => date("d/m/Y", strtotime($event->date)),
                                    "literal_date" => $calendrier->days[date("N", strtotime($event->date))] . ' ' . date("d", strtotime($event->date)) .' '. $calendrier->toString(),
                                    "voiture" => $event->voiture,
                                    "user_id" => $event->id_user,
                                    "user_initiales" => $user->macaron(),
            ];


            //$translated_eventType=  json_decode($event->getEventType(), true);
            $user_events[$sj]['eventType_id'] = $event->id_type;
            $user_events[$sj]['translated_eventType'] = $liste_type_event[$event->id_type]['titre'];

        }
        ksort($user_events);
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



        return $this->view->render($response,'calendrier.twig', [

                                                                    "user" => $user,
                                                                    "user_events" => $user_events,
                                                                    "calendrier" => $calendrier,
                                                                    "liste_type_event" => $liste_type_event,
                                                                    "month" => $month,
                                                                    "year" => $year,

                                                                ]);


    }


    public function update($request, $response, array $args)
    {
        $postArr = $request->getParsedBody();
        $month = $postArr['selMois'];
        $year = $postArr['selAnnee'];

        $calendrier = new Calendar($month, $year);
       /* $this->flash->addMessage('info', 'Mise a jour OK top');
        $flash = $this->flash->getMessages('info');
*/
        return $this->view->render($response,'calendrier.twig', [
                                                                    $data,
                                                                    "calendrier" => $calendrier,

                                                                ]);
       //return $response->withRedirect($this->router->pathFor('calendar.general'));
    }
}
