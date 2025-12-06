<?php

namespace app\controllers;

use DateTime;
use Flight;

class EmployeController {

	public function __construct() {

	}

    public function chooseConsultation()
    {
        Flight::render('employe/choix_consultation', [] , 'contenu');
        Flight::render('shared/home');
    }

    public function chooseSoumission()
    {
        Flight::render('employe/choix_soumission', [] , 'contenu');
        Flight::render('shared/home');
    }

    public function chooseAttestation()
    {
        Flight::render('employe/demande_attestation' , [] , 'contenu');
        Flight::render('shared/home');
    }

    public function versRemboursement()
    {
        Flight::render('employe/remboursement', [] , 'contenu');
        Flight::render('shared/home');
    }
}