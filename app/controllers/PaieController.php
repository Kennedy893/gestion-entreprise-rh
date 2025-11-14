<?php

namespace app\controllers;


use Flight;

class PaieController
{

    public function __construct() {}

    public function etatDePaie() {
        $date = date('Y-m-d');
        $data = Flight::PaieModel()->getContratEmployeByDate($date);
        Flight::render("paie/etat_paie", ['data' => $data]);
    }

    
}
