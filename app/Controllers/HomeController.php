<?php

namespace App\Controllers;

use App\Models\User;
use App\Auth\Auth;
use App\Controllers\Controller;
use Illuminate\Database\Capsule\Manager as DB;

/**
 * HomeController
 *
 * @author    Haven Shen <havenshen@gmail.com>
 * @copyright    Copyright (c) Haven Shen
 */
class HomeController extends Controller
{


    public function index($request, $response, array $args)
    {
        $current_month = intval(date('m'));
        $current_year = intval(date('Y'));
        $listing = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
        $month_listing = [];
        $year_listing = [];

        foreach (range(1, 12) as $number) {
            $month_listing[$number] = $listing[$number-1];
        }
        foreach (range(-2, 20) as $number) {
            $year_listing[$current_year+$number] = $current_year+$number;
        }



        /**
         * Recup du user session auhtentifié
         */

        $user = $this->auth->user();
        if($user){

        $user->setPrenom($user->prenom);
        $user->setNom($user->nom);
        $user->setEmail($user->email);
        $args['user'] = $user;
        }




        $args['form_calendar']= [
                                    "current_month"=>$current_month,
                                    "current_year" =>$current_year,
                                    "month_listing" => $month_listing,
                                    "year_listing"=> $year_listing,
                                ];

        return $this->view->render($response,'home.twig', $args );
    }
}
