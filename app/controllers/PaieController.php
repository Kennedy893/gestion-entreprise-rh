<?php

namespace app\controllers;

use Flight;

class PaieController
{
    public function __construct() {}

    public function etatDePaie()
    {
        $date = date('Y-m-d');
        $data = Flight::PaieModel()->getContratEmployeByDate($date);
        Flight::render("paie/etat_paie", ['data' => $data]);
    }

    public function fichePaie($id_emp)
    {
        $date = date('Y-m-d');
        $data = Flight::PaieModel()->getContratEmployeByIdEmploye($id_emp,$date);
        Flight::render("paie/fiche_paie" , ['emp' => $data]);
    }

    public function detailsEmp()
    {
        $id_emp = 1;
        $date = date('Y-m-d');
        $data = Flight::PaieModel()->getContratEmployeByIdEmploye($id_emp,$date);
        Flight::json($data);
    }
}
