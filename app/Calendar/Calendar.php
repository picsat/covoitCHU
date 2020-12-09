<?php

namespace App\Calendar;

use App\Models\User;
use App\Auth\Auth;

/**
 * Calendar
 *
 * @author    Haven Shen <havenshen@gmail.com>
 * @copyright    Copyright (c) Haven Shen
 */
class Calendar
{

    public $month;
    public $year;
    public $months = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
    public $days = ['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche'];


    public function __construct($month = null, $year = null)
    {

        if($month === null){
            $month = intval(date('m'));
        }

        if($year === null){
            $year = intval(date('Y'));
        }

        /*if($month < 1 || $month > 12){
            throw new \Exception("Le mois $month n'est pas valide");
        }*/

        if($month < 1) {
            $month = 12;
        }

         if($month > 12) {
            $month = 1;
        }

        if($year < 1970 ){
            throw new \Exception("L'année $year doit etre > 1970");
        }

        foreach (range(1, 12) as $number) {
            $month_listing[$number] = $this->listingMonth[$number-1];
        }

        $this->date_today = date('d/m/Y');
        $this->month = $month;
        $this->year = $year;

        /*$this->month_literal = $this->listingMonth;
        $this->month_literal = $month_listing[$month];*/
    }

    /**
     * Renvoie le Premier jour du mois
     */
    public function getFirstDay(): \DateTime
    {
        return new \DateTime("{$this->year}-{$this->month}-01");
    }

     /**
     * Renvoie le Premier jour du mois
     */
    public function getLastDay(): \DateTime
    {
        $end = (clone $this->getFirstDay())->modify('+1 month -1 day');
        return $end;
    }


    /**
     * Retourne mois + année en tte lettre
     */
    public function toString() : string
    {
        return $this->months[$this->month - 1] . ' ' . $this->year;
    }

    /**
     * Renvoie le nombre de semaine
     */
    public function getWeeks()
    {
        $start = $this->getFirstDay();
        $end = $this->getLastDay();
        // var_dump($start,$end);
        $weeks =  intval($end->format('W')) - intval($start->format('W')) + 1;

        if($weeks < 0) {
            $weeks = intval($end->format('W'));
        }

        return $weeks;
    }

    /**
     * Renvoi le numero de jour par rapport a la date du 01 dans le mois
     * @ weeknum (int) : numero de la semaine dans le mois
     * @ day (int) : numero du jour de la semaine.
     */
    public function getday(int $weekNum=0, int $numDay=0) : \DateTime
    {

        $last_monday = $this->getFirstDay()->modify("last monday");

        // si on a le last monday qui vaut exatement -7j depuis un lundu 1er, il faut supprimer cette semaine "precedente"
        if($last_monday = clone($this->getFirstDay()->modify("Monday this week -7 days"))) {
            $last_monday = clone($this->getFirstDay()->modify("Monday this week"));
        }

        $week_days_cnt = count($this->days);
        $weekNum = intval($weekNum);

        $day = $last_monday->modify('+'. ($numDay + $weekNum * $week_days_cnt) .' days') ;

        return $day;

    }


    public function withinMonth(\DateTime $date)
    {

        return $this->getFirstDay()->format('Y-m') === $date->format('Y-m');
    }


}
