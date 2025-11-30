<?php

namespace app\controllers;

use DateTime;
use Flight;

class EmployeController {

	public function __construct() {

	}

    public function chooseConsultation()
    {
        Flight::render('employe/choix_consultation');
    }

    public function chooseSoumission()
    {
        Flight::render('employe/choix_soumission');
    }

    public function chooseAttestation()
    {
        Flight::render('employe/demande_attestation');
    }

    public function versRemboursement()
    {
        Flight::render('employe/remboursement');
    }
}