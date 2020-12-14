<?php

namespace App\Controllers\Calendar;

use App\Models\User;
use App\Models\Event;
use App\Calendar\Calendar;
use App\Auth\Auth;
use App\Controllers\Controller;
use Illuminate\Database\Capsule\Manager as DB;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
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

        /** On peut arriver en GET ou POST sur le controller
         *  si Post -> route post:calendrier
         *  si GET  -> route get:calendrier[/{month}-{year}}
         *  si GET  -> route get:calendrier on passe le mois en cours
         *  si GET  -> route get:calendrier/ -> erreur
         */
        $GETmonth = $args['month'];
        $GETyear = $args['year'];

         //recup des détes passée en POST
        $postArr = $request->getParsedBody();
        $POSTmonth = $postArr['selMois'];
        $POSTyear = $postArr['selAnnee'];

        if ($POSTmonth != null && $POSTyear != null) {
            $calendrier = new Calendar($POSTmonth, $POSTyear);
        }
        else if($GETmonth != null && $GETyear !=null ){
            $calendrier = new Calendar($GETmonth, $GETyear);
        }
        else {
            $month = date("m");
            $year = date("Y");
            $calendrier = new Calendar($month, $year);
           // throw new \Exception("calendrier no possiblo !");
        }


        /**
         * Recupération des event du mois du calendrier en cours pour le user indentifié
         * renvoie un tableau d'event contenant les events et les info users si besoin
         */

        $user = $this->auth->user();

        $user_events = array();

        // On recupère la liste des types qui alimente les <select> du tpl
        $RecupListeTypeEvent = new \App\Models\Event;
        $EventTypes = json_decode($RecupListeTypeEvent->getAllEventType());

        foreach ($EventTypes as $type) {
             $liste_type_event[$type->id] = [
                                                "titre"=> $type->titre,
                                                "description"=> $type->description,
                                            ];
        }

        // On part du principe que c'est a partir des Events du mois/annee en cours qu'il faut aller chercher les relations (User / type_event...)
        // renvoi un tableau d'Events

        $firstDayMonth = $calendrier->getFirstDay()->format('Y-m-d H:i:s');
        $lastDayMonth = $calendrier->getLastDay()->format('Y-m-d H:i:s');
        $userEvents = \App\Models\Event::with('user')->where('id_user','=',$user->id)->whereBetween('date', [$firstDayMonth, $lastDayMonth])->get();
        ///var_dump($userEvents);

        // Retourner un tableau d'events
        // @sj = $key = date event sous la forme array[AAAAMMJJ]
        foreach ($userEvents as $event) {

            $sj = date("Ymd", strtotime($event->date));

            $voitureBoolean = ($event->voiture) ? true : false;

            $user_events[$sj] =  [
                                    "id" => $event->id,
                                    "date" => $event->date,
                                    "translated_date" => date("d/m/Y", strtotime($event->date)),
                                    "literal_date" => $calendrier->days[date("N", strtotime($event->date))] . ' ' . date("d", strtotime($event->date)) .' '. $calendrier->toString(),
                                    "voiture" => $voitureBoolean,
                                    "user_id" => $event->id_user,
                                    "user_initiales" => $user->macaron(),
                                    "eventType_id" => $event->id_type,
                                    "translated_eventType" => $liste_type_event[$event->id_type]['titre'],
            ];
        }

        ksort($user_events); // -- pour la mode

        // Autre Possibilité : on part du principe que c'est a partir du User en cours pour aller chercher les relations ses Events (User / type_event...)
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
                                                                    "data"=> $postArr,
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
        $errors = array();

        $postArr = $request->getParsedBody();
        $month = $postArr['selMois'];
        $year = $postArr['selAnnee'];

        $user = $this->auth->user();
            // put log message
            $this->logger->info("**** SAVE CALENDAR **** : $month/$year pour $user->nom $user->prenom {\"id\":\"$user->id\"}");

        $updateForm = array();
        $calendrier = new Calendar($month, $year);

        $firstDayMonth = $calendrier->getFirstDay()->format('Y-m-d H:i:s');
        $lastDayMonth = $calendrier->getLastDay()->format('Y-m-d H:i:s');


        /*
        $this->flash->addMessage('error', 'bablabla details');
        return $response->withRedirect($this->router->pathFor('calendar.general',['month'=>$month, 'year'=>$year]));
        */

        /*
        $this->flash->addMessage('warning', 'bablabla details');
        return $response->withRedirect($this->router->pathFor('calendar.general',['month'=>$month, 'year'=>$year]));
        */

        $userEvents = \App\Models\Event::with('user')->where('id_user','=',$user->id)->whereBetween('date', [$firstDayMonth, $lastDayMonth])->get();
        $user_events = array();
        // Retourner un tableau d'events
        // @sj = $key = date event sous la forme array[AAAAMMJJ]
        foreach ($userEvents as $event) {

            $sj = date("Ymd", strtotime($event->date));

            $voitureBoolean = ($event->voiture) ? true : false;

            $user_events[$sj] =     [
                                        "id" => $event->id,
                                        "type_id" => $event->id_type,
                                        "date" => $event->date,
                                        "voiture" => $voitureBoolean,
                                        "user_id" => $user->id,
                                    ];
        }
        ksort($user_events);

        $updateForm['db'] = $user_events;




        $nb_weeks = $calendrier->getWeeks();
        $days = $calendrier->days;

        for($w=0; $w < $nb_weeks ; $w++)
        {
            foreach ($days as $k => $day)
            {
                $date = $calendrier->getday($w,$k);
                $dateKey = $calendrier->getday($w,$k)->format('Ymd');
                $selectKey = 'sj'. $calendrier->getday($w,$k)->format('d');


                // au cas ou ....
                if($calendrier->withinMonth($date))
                {
                    // ---- si on a des selects en sjXX de postés
                    if($postArr[$selectKey])
                    {
                        // -- Checkbox voiture boolean
                        $voitureBoolean = ($postArr["voiture_".$selectKey]) ? true : false;

                        //-- si on a la date clé dans les $userEvents --> a Update
                        if(array_key_exists($dateKey,$updateForm['db']))
                        {
                            $updateForm['submited']['toUpdate'][$dateKey] =     [
                                                                                    "id" =>  $user_events[$dateKey]["id"], // recup id du Event existant
                                                                                    "type_id" => $postArr[$selectKey],
                                                                                    "date" => $calendrier->getday($w,$k)->format('Y-m-d H:i:s'),
                                                                                    "voiture" => $voitureBoolean,
                                                                                    "user_id" => $user->id,
                                                                                ];

                        //-- sinon c'est des nouveaux Events --> a créer
                        }
                        else
                        {
                            $updateForm['submited']['toCreate'][$dateKey] =     [
                                                                                    "id" =>  "new",
                                                                                    "type_id" => $postArr[$selectKey],
                                                                                    "date" => $calendrier->getday($w,$k)->format('Y-m-d H:i:s'),
                                                                                    "voiture" => $voitureBoolean,
                                                                                    "user_id" => $user->id,
                                                                                ];
                        }
                    // ---- Le reste est vide donc si existants en db -> a Delete
                    } else {
                        if(array_key_exists($dateKey,$updateForm['db']))
                        {
                             $updateForm['submited']['toDelete'][$dateKey] =    [
                                                                                    "id" =>  $user_events[$dateKey]["id"], // recup id du Event existant
                                                                                    "type_id" => $postArr[$selectKey],
                                                                                    "date" => $calendrier->getday($w,$k)->format('Y-m-d H:i:s'),
                                                                                    "voiture" => $voitureBoolean,
                                                                                    "user_id" => $user->id,
                                                                                ];
                        }
                    }
                }
            }
        }
        ksort($updateForm);

        // Traitements
        if(count($updateForm['submited']) > 0)
        {
            $retour = array();

            // -- *** UPDATE *** si on a des update, c'est qu'on a des events en DB sur ce mois
            if((count($updateForm['submited']['toUpdate']) > 0) && (count($updateForm['db']) > 0))
            {
                $eventUpdate = $updateForm['submited']['toUpdate'];
                $count_eventUpdate = 0;
                foreach ($eventUpdate as $e => $event) {
                    //comparons si le type ou si la voiture a changé entre les 2 events
                    $message = "";
                    if(($updateForm['db'][$e]['type_id'] != $event['type_id']) || ($updateForm['db'][$e]['voiture'] != $event['voiture']))
                    {
                        $eventDb = $updateForm['db'][$e];
                        $data = Event::where('id', $eventDb['id'])->update([
                            'id_type' => $event['type_id'],
                            'voiture' => $event['voiture'],
                            'last_update' => date('Y-m-d H:i:s'),
                        ]);


                        $message .= "UPDATE Event : " . $event['date'] . " {\"id\":\"". $eventDb['id'] ."\"} / $user->nom $user->prenom {\"id\":\"$user->id\"} "
                                 . "          " . " type_id : " . $eventDb['type_id'] . "---->" . $event['type_id']."\r"
                                 . "          " . " voiture : " . $eventDb['voiture'] . "---->" . $event['voiture']."\n";
                        //$errors[] .= $message ;
                        $this->logger->info($message);

                        $count_eventUpdate ++;
                    }
                }

                ($count_eventUpdate > 0) ? $retour['warning'] .= ('<strong>('. $count_eventUpdate .')</strong> ont été modifiés') : "";
            }

            // -- *** DELETE *** si on a des delete, c'est qu'on a des events en DB sur ce mois
            if((count($updateForm['submited']['toDelete']) > 0) && (count($updateForm['db']) > 0))
            {
                $eventDelete = $updateForm['submited']['toDelete'];
                foreach ($eventDelete as $e => $event) {
                    // On dezingue l'event
                    $message = "";

                        $eventDb = $updateForm['db'][$e];
                        $data = Event::destroy($eventDb['id']);

                        $message .= "DELETE Event : " . $event['date'] . " {\"id\":\"". $eventDb['id'] ."\"} / $user->nom $user->prenom {\"id\":\"$user->id\"} R.I.P ";
                        //$errors[] .= $message ;
                        $this->logger->info($message);

                }

                (count($eventDelete) > 0) ? $retour['error'] .= ('<strong>('. count($eventDelete) .')</strong> ont été supprimés') : "";
            }
            // -- *** CREATE *** si on a des create, c'est qu'on avait PAS ces events en DB sur ce mois
            if(count($updateForm['submited']['toCreate']) > 0)
            {
                $eventCreate = $updateForm['submited']['toCreate'];
                foreach ($eventCreate as $e => $event) {
                    // On creer l'event
                    $message = "";

                        $data = Event::create([
                            "date" => $event['date'],
                            "id_user" => $user->id,
                            "id_type" => $event['type_id'],
                            "voiture" => $event['voiture'],
                            "inscription" => 0,
                            "creation_date" => date('Y-m-d H:i:s'),
                            'last_update' => date('Y-m-d H:i:s'),
                        ]);
                        $insertedId = $data->id;



                        $message .= "CREATE Event : " . $event['date'] . " {\"id\":\"". $insertedId ."\"} / $user->nom $user->prenom {\"id\":\"$user->id\"} "
                        . "          " . " type_id : " . $event['type_id']."\r"
                        . "          " . " voiture : " . $event['voiture']."\n";
                        //$errors[] .= $message ;
                        $this->logger->info($message);

                }
                (count($eventCreate) > 0) ? $retour['success'] .= ('<strong>('. count($eventCreate) .')</strong> ont été insérés') : "";
            }


            // Message Flash de retours sur le CRUD
            if( $count_eventUpdate > 0 || (count($updateForm['submited']['toCreate']) > 0) || (count($updateForm['submited']['toDelete']) > 0))
            {
                $this->flash->addMessage('info', 'Votre Calendrier mis à jour avec succès');
                foreach ($retour as $l => $ligne) {
                    $this->flash->addMessage($l,$ligne);
                }
            }
        } // --eof traitements


        // Soit on a Ajaxé le POST liens de navigation précédent/suivant
        if ($request->isXHR()) {
            return $response;
        }
        // Soit Submit normal
        else
        {
            return $response->withRedirect($this->router->pathFor('calendar.general', ['month'=>$month, 'year'=>$year]));
        }
    }
}
